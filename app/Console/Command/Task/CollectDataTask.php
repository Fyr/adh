<?php
App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');
App::uses('Settings', 'Model');
App::uses('Task', 'Model');
App::uses('Campaign', 'Model');
App::uses('CampaignStats', 'Model');
App::uses('PlugrushApi', 'Model');
App::uses('PopadsApi', 'Model');
App::uses('VoluumApi', 'Model');
class CollectDataTask extends AppShell {
    public $uses = array('Settings', 'Task', 'Campaign', 'CampaignStats', 'PlugrushApi', 'PopadsApi', 'VoluumApi');

    public function execute() {
        $this->Settings->initData();

        $this->Task->setProgress($this->id, 0, 3); // 3 subtasks
        $this->Task->setStatus($this->id, Task::RUN);

        // т.к. мы получаем данные в реал-тайме но в зав-ти от таймзоны
        // нам важно просто получать данные за день
        Configure::write('date', array(
            'from' => time() - DAY,
            'to' => time() + DAY
        ));

        $aSubtasks = array(
            array('name' => 'PlugrushAPI', 'method' => '_processPlugrush'),
            array('name' => 'PopAdsAPI', 'method' => '_processPopads'),
            array('name' => 'VoluumAPI', 'method' => '_processVoluum')
        );
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
                            'ctr' => round(intval($row['clicks']) / $campaign['src_visits'] * 100), // 0.00%
                            'roi' => round($profit / $campaign['cost'] * 100),
                            'epv' => floatval($row['revenue']) / $campaign['src_visits'],
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
}
