<?php
App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');

App::uses('Settings', 'Model');
App::uses('Task', 'Model');
App::uses('Campaign', 'Model');
App::uses('CampaignStats', 'Model');
App::uses('Domain', 'Model');
App::uses('CampaignDomain', 'Model');
App::uses('DomainStats', 'Model');

App::uses('PlugrushApi', 'Model');
App::uses('PopadsApi', 'Model');
App::uses('VoluumApi', 'Model');
class CollectDataTask extends AppShell {
    public $uses = array(
        'Settings', 'Task', 'Campaign', 'CampaignStats', 'Domain', 'CampaignDomain', 'DomainStats',
        'PlugrushApi', 'PopadsApi', 'VoluumApi'
    );

    public function execute() {
        $this->Settings->initData();

        // т.к. мы получаем данные в реал-тайме но в зав-ти от таймзоны
        // нам важно просто получать данные за день
        Configure::write('date', array(
            'from' => time() - DAY,
            'to' => time() + DAY
        ));

        $aSubtasks = array(
            array('name' => 'PlugrushAPI', 'method' => '_processPlugrush'),
            array('name' => 'PopAdsAPI', 'method' => '_processPopads'),
            array('name' => 'VoluumAPI', 'method' => '_processVoluum'),
            array('name' => 'PlugrushDomains', 'method' => '_processPlugrushDomains')
        );
        $this->Task->setProgress($this->id, 0, count($aSubtasks));
        $this->Task->setStatus($this->id, Task::RUN);
        foreach($aSubtasks as $i => $subtask) {
            $subtask_id = $this->Task->add(0, $subtask['name'], null, $this->id);
            $this->Task->setData($this->id, 'subtask_id', $subtask_id);
            try {
                $this->{$subtask['method']}($subtask_id);
            } catch (Exception $e) {
                $status = $this->Task->getStatus($this->id);
                if ($status == Task::ABORT) {
                    $this->Task->setStatus($subtask_id, Task::ABORTED);
                    throw $e;
                } else {
                    $this->Task->setData($subtask_id, 'xdata', $e->getMessage());
                    $this->Task->setStatus($subtask_id, Task::ERROR);
                    $this->out(mb_convert_encoding($e->getMessage(), 'cp1251', 'utf8'));
                }
            }
            $this->Task->setProgress($this->id, ++$i);
            $this->Task->saveStatus($this->id);
        }

        $this->Task->setStatus($this->id, Task::DONE);
    }

    private function _processPlugrush($subtask_id) {
        $aData = $this->PlugrushApi->getCampaignList();
        $this->Task->setProgress($subtask_id, 0, count($aData));
        $this->Task->setStatus($subtask_id, Task::RUN);
        foreach($aData as $i => $row) {
            $status = $this->Task->getStatus($this->id);
            if ($status == Task::ABORT) {
                throw new Exception(__('Processing was aborted by user'));
            }

            $campaign = $this->Campaign->findBySrcTypeAndSrcId(Campaign::TYPE_PLUGRUSH, $row['id']);

            $data = array();
            if ($campaign) {
                $data['id'] = $campaign['Campaign']['id']; // обновляем кампанию
            } else {
                $data = array(
                    'src_type' => Campaign::TYPE_PLUGRUSH,
                    'src_id' => $row['id'],
                    'src_name' => $row['title'],
                    'src_uid' => $this->VoluumApi->getCampaignUID($row['url']),
                    'url' => $row['url']
                );
            }
            $data['active'] = ($row['status'] == 'running');
            $data['status'] = $row['status'];
            $data['bid'] = floatval($row['bid']) * 1000;
            $data['src_visits'] = intval($row['traffic_received']);
            // $data['src_clicks'] = intval($row['traffic_received']);
            $data['cost'] = floatval($row['spent']);
            $data['src_data'] = serialize($row);
            if ($data['active']) {
                $data['trk_data'] = null;
            }
            $this->Campaign->clear();
            $this->Campaign->save($data);

            $this->Task->setProgress($subtask_id, $i + 1);

            $_progress = $this->Task->getProgressInfo($subtask_id);
            $progress = $this->Task->getProgressInfo($this->id);
            $this->Task->setProgress($this->id, $progress['progress'] + $_progress['percent'] * 0.01);
        }

        $this->Task->setStatus($subtask_id, Task::DONE);
    }

    private function _processPopads($subtask_id) {
        $aData = $this->PopadsApi->getCampaignList();
        $this->Task->setProgress($subtask_id, 0, count($aData));
        $this->Task->setStatus($subtask_id, Task::RUN);
        foreach($aData as $i => $row) {
            $status = $this->Task->getStatus($this->id);
            if ($status == Task::ABORT) {
                throw new Exception(__('Processing was aborted by user'));
            }

            $campaign = $this->Campaign->findBySrcTypeAndSrcId(Campaign::TYPE_POPADS, $row['id']);

            $data = array();
            if ($campaign) {
                $data['id'] = $campaign['Campaign']['id']; // обновляем кампанию
            } else {
                $data = array(
                    'src_type' => Campaign::TYPE_POPADS,
                    'src_id' => $row['id'],
                    'src_name' => $row['name'],
                    'src_uid' => $this->VoluumApi->getCampaignUID($row['url'][0]),
                    'url' => $row['url'][0]
                );
            }
            $data['active'] = ($row['status'] == 'approved');
            $data['status'] = $row['status'];
            /*
            $data['bid'] = floatval($row['bid']) * 1000;
            $data['src_visits'] = intval($row['traffic_received']);
            // $data['src_clicks'] = intval($row['traffic_received']);
            $data['cost'] = floatval($row['spent']);
            */
            $data['src_data'] = serialize($row);
            if ($data['active']) {
                $data['trk_data'] = null;
            }

            $this->Campaign->clear();
            $this->Campaign->save($data);

            $this->Task->setProgress($subtask_id, $i + 1);

            $_progress = $this->Task->getProgressInfo($subtask_id);
            $progress = $this->Task->getProgressInfo($this->id);
            $this->Task->setProgress($this->id, $progress['progress'] + $_progress['percent'] * 0.01);
        }

        $this->Task->setStatus($subtask_id, Task::DONE);

    }

    private function _processVoluum($subtask_id) {
        $aTrackerCampaigns = $this->VoluumApi->getTrackerCampaignList();
        $aTrkData = array(
            'plugrush' => array(),
            'popads' => array()
        );
        $aUIDs = array();
        foreach($aTrackerCampaigns as $data) {
            $src_type = strtolower($data['trafficSource']);
            if (in_array($src_type, array_keys($aTrkData))) { // пока можем обработать только PlugRush, PopAds

                // для статистики по кампании нужен только URL и campaignId (это и есть UID)
                $uid = $this->VoluumApi->getCampaignUID($data['campaignUrl']);
                $aTrkData[$src_type][$uid] = $data['campaignUrl'];
                $aUIDs[] = $uid;
            }
        }
        $aCampaigns = $this->Campaign->findAllBySrcTypeAndSrcUid(array_keys($aTrkData), $aUIDs);
        $aSrcData = array();
        // Получить массив кампаний-источников в разрезе src_id для связывания по трэкеру
        foreach($aCampaigns as $campaign) {
            $campaign = $campaign['Campaign'];
            $src_type = $campaign['src_type'];
            $uid = $campaign['src_uid'];
            $src_id = $campaign['src_id'];
            if ($url = Hash::get($aTrkData, $src_type.'.'.$uid)) { // кампания-источник соотв-ет по типу и UID
                // в трэкере ей может соотв-ть несколько кампаний источников
                $aSrcData[$src_type][$uid][$src_id] = $campaign;
            }
        }

        $this->Task->setProgress($subtask_id, 0, count($aUIDs));
        $this->Task->setStatus($subtask_id, Task::RUN);

        $i = 0;
        foreach($aTrkData as $src_type => $trkData) {
            foreach($trkData as $uid => $url) {
                $status = $this->Task->getStatus($this->id);
                if ($status == Task::ABORT) {
                    throw new Exception(__('Processing was aborted by user'));
                }

                $stats = $this->VoluumApi->getCampaignDetailedList($url);
                foreach($stats as $row) {
                    if ($campaign = Hash::get($aSrcData, $src_type.'.'.$uid.'.'.$row['src_id'])) {
                        $profit = floatval($row['revenue']) - $campaign['cost'];
                        $data = array(
                            // доп.данные для сохранения в статистику
                            'campaign_id' => $campaign['id'],
                            'src_visits' => $campaign['src_visits'],
                            'src_clicks' => $campaign['src_clicks'],
                            'cost' => $campaign['cost'],
                            'src_data' => $campaign['src_data'],

                            // данные из трэкера - в кампанию и статистику
                            'trk_visits' => intval($row['visits']),
                            'trk_clicks' => intval($row['clicks']),
                            'conversion' => intval($row['conversions']),
                            'revenue' => floatval($row['revenue']), // $0.00
                            'profit' => $profit, // $0.00
                            'cpv' => $campaign['cost'] / $campaign['src_visits'], // $0.0000
                            'ctr' => ($campaign['src_visits']) ? round(intval($row['clicks']) / $campaign['src_visits'] * 100) : 0, // 0.00%
                            'roi' => ($campaign['cost']) ? round($profit / $campaign['cost'] * 100) : 0,
                            'epv' => ($campaign['src_visits']) ? floatval($row['revenue']) / $campaign['src_visits'] : 0,
                            'is_trk_data' => true,
                            'trk_data' => serialize($row)
                        );

                        $this->CampaignStats->clear();
                        $this->CampaignStats->save($data);

                        $data['id'] = $campaign['id']; // для сохранения в нужную кампанию
                        $this->Campaign->clear();
                        $this->Campaign->save($data);
                    }
                }

                $this->Task->setProgress($subtask_id, ++$i);
                $_progress = $this->Task->getProgressInfo($subtask_id);
                $progress = $this->Task->getProgressInfo($this->id);
                $this->Task->setProgress($this->id, $progress['progress'] + $_progress['percent'] * 0.01);
            }
        }

        $this->Task->setStatus($subtask_id, Task::DONE);
    }

    private function _getCampaignList($src_type) {
        $aData = $this->Campaign->findAllBySrcType($src_type, array('id', 'src_id', 'src_uid', 'url'));
        return $aData;
    }

    private function _processPlugrushDomains($subtask_id) {
        $aCampaigns = $this->_getCampaignList(PlugrushApi::TYPE);
        $aSrcData = array();
        $aTrkData = array();

        $this->Task->setProgress($subtask_id, 0, count($aCampaigns));
        $this->Task->setStatus($subtask_id, Task::RUN);

        foreach($aCampaigns as $i => $campaign) {
            $campaign = $campaign['Campaign'];
            try {
                $src_id = $campaign['src_id'];
                $url = $campaign['url'];

                $aSrcData[$src_id] = $this->PlugrushApi->getDomainStats($src_id);
                $aDomains = Hash::combine($aSrcData[$src_id], '{n}.domain_id', '{n}.domain');
                $aDomainID = $this->_addDomains(PlugrushApi::TYPE, $campaign['id'], $aDomains);

                $aTrkData[$src_id] = $this->VoluumApi->getDomainList($url, $src_id, count($aDomainID));
                $aDomains = Hash::combine($aSrcData[$src_id], '{n}.domain_id', '{n}');
                $aTrkDomains = Hash::combine($aTrkData[$src_id], '{n}.domain', '{n}');
                foreach($aDomains as $domain_uid => $stats) {
                    $status = $this->Task->getStatus($this->id);
                    if ($status == Task::ABORT) {
                        throw new Exception(__('Processing was aborted by user'));
                    }

                    if (isset($aDomainID[$domain_uid])) {
                        $data = array(
                            'campaign_id' => $campaign['id'],
                            'domain_id' => $aDomainID[$domain_uid],
                            'src_visits' => intval($stats['uniques']),
                            'cost' => floatval($stats['amount']),
                            'src_data' => serialize($stats),
                            'is_trk_data' => false
                        );

                        if (isset($aTrkDomains[$domain_uid])) {
                            $row = $aTrkDomains[$domain_uid];
                            $profit = floatval($row['revenue']) - $data['cost'];
                            $data['trk_visits'] = intval($row['visits']);
                            $data['trk_clicks'] = intval($row['clicks']);
                            $data['conversion'] = intval($row['conversions']);
                            $data['revenue'] = floatval($row['revenue']);
                            $data['profit'] = $profit; // $0.00
                            $data['cpv'] = $data['cost'] / $data['src_visits']; // $0.0000
                            $data['ctr'] = ($data['src_visits']) ? round(intval($row['clicks']) / $data['src_visits'] * 100) : 0; // 0.00%
                            $data['roi'] = ($data['cost']) ? round($profit / $data['cost'] * 100) : 0;
                            $data['epv'] = ($data['src_visits']) ? floatval($row['revenue']) / $data['src_visits'] : 0;
                            $data['trk_data'] = serialize($row);
                            $data['is_trk_data'] = true;
                        }

                        $this->DomainStats->clear();
                        $this->DomainStats->save($data);
                    }
                }
            } catch (Exception $e) {
                $status = $this->Task->getStatus($this->id);
                if ($status == Task::ABORT) {
                    throw $e;
                } // иначе просто продолжаем обработку
            }
            $this->Task->setProgress($subtask_id, ++$i);
            $_progress = $this->Task->getProgressInfo($subtask_id);
            $progress = $this->Task->getProgressInfo($this->id);
            $this->Task->setProgress($this->id, $progress['progress'] + $_progress['percent'] * 0.01);
        }
        $this->Task->setStatus($subtask_id, Task::DONE);
    }

    /**
     * @param $src_type - тип источника
     * @param $campaign_id - внутр.ID кампании
     * @param $aDomains - список доменов в виде domain_uid => domain
     * @return mixed - список доменов в виде domain_uid => внутр.ID домена
     */
    private function _addDomains($src_type, $campaign_id, $aDomains) {
        $fields = array('domain_uid', 'id');
        $conditions = compact('src_type');
        $aExists = $this->Domain->find('list', compact('fields', 'conditions'));
        $ids = $this->Campaign->getDomainIds($campaign_id);
        foreach($aDomains as $domain_uid => $domain) {
            if (!isset($aExists[$domain_uid])) {
                $this->Domain->clear();
                $this->Domain->save(compact('src_type', 'domain', 'domain_uid'));
                $domain_id = $this->Domain->id;
                $aExists[$domain_uid] = $domain_id;
            } else {
                $domain_id = $aExists[$domain_uid];
            }

            if (!in_array($domain_id, $ids)) {
                $this->CampaignDomain->clear();
                $this->CampaignDomain->save(compact('campaign_id', 'domain_id'));
            }
        }
        return $aExists;
    }
}
