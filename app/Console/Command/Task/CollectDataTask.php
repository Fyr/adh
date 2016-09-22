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
        // $this->loadModel('Settings');
        $this->Settings->initData();

        $this->Task->setProgress($this->id, 0, 3); // 3 subtasks
        $this->Task->setStatus($this->id, Task::RUN);

        // ò.ê. ìû ïîëó÷àåì äàííûå â ğåàë-òàéìå íî â çàâ-òè îò òàéìçîíû
        // íàì âàæíî ïğîñòî ïîëó÷àòü äàííûå çà äåíü
        Configure::write('date', array(
            'from' => time() - DAY,
            'to' => time() + DAY
        ));

        $i = 1;
        try {
            $this->_processPlugrush();
            $this->Task->setProgress($this->id, ++$i);
        } catch (Exception $e) {
        }

        try {
            $this->_processPopads();
            $this->Task->setProgress($this->id, ++$i);
        } catch (Exception $e) {
        }

        try {
            $this->_processVoluum();
            $this->Task->setProgress($this->id, ++$i);
        } catch (Exception $e) {
        }

        // $this->Task->setData($this->id, 'xdata', $total * 3);
        $this->Task->setStatus($this->id, Task::DONE);
    }

    private function _processPlugrush() {
        $aData = $this->PlugrushApi->getCampaignList();
        foreach($aData as $row) {
            $campaign = $this->Campaign->findBySrcTypeAndSrcId(Campaign::TYPE_PLUGRUSH, $row['id']);

            $data = array();
            if ($campaign) {
                $data['id'] = $campaign['Campaign']['id']; // îáíîâëÿåì êàìïàíèş
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

            $this->Campaign->clear();
            $this->Campaign->save($data);
        }
    }

    private function _processPopads() {
        $aData = $this->PopadsApi->getCampaignList();
        foreach($aData as $row) {
            $campaign = $this->Campaign->findBySrcTypeAndSrcId(Campaign::TYPE_POPADS, $row['id']);

            $data = array();
            if ($campaign) {
                $data['id'] = $campaign['Campaign']['id']; // îáíîâëÿåì êàìïàíèş
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

            $this->Campaign->clear();
            $this->Campaign->save($data);
        }
    }

    private function _processVoluum() {
        $aTrackerCampaigns = $this->VoluumApi->getTrackerCampaignList();
        $aTrkData = array(
            'plugrush' => array(),
            'popads' => array()
        );
        $aUIDs = array();
        foreach($aTrackerCampaigns as $data) {
            $src_type = strtolower($data['trafficSource']);
            if (in_array($src_type, array_keys($aTrkData))) { // ïîêà ìîæåì îáğàáîòàòü òîëüêî PlugRush, PopAds

                // äëÿ ñòàòèñòèêè ïî êàìïàíèè íóæåí òîëüêî URL è campaignId (ıòî è åñòü UID)
                $uid = $this->VoluumApi->getCampaignUID($data['campaignUrl']);
                $aTrkData[$src_type][$uid] = $data['campaignUrl'];
                $aUIDs[] = $uid;
            }
        }
        $aCampaigns = $this->Campaign->findAllBySrcTypeAndSrcUid(array_keys($aTrkData), $aUIDs);
        unset($aUIDs);
        $aSrcData = array();
        // Ïîëó÷èòü ìàññèâ êàìïàíèé-èñòî÷íèêîâ â ğàçğåçå src_id äëÿ ñâÿçûâàíèÿ ïî òğıêåğó
        foreach($aCampaigns as $campaign) {
            $campaign = $campaign['Campaign'];
            $src_type = $campaign['src_type'];
            $uid = $campaign['src_uid'];
            $src_id = $campaign['src_id'];
            if ($url = Hash::get($aTrkData, $src_type.'.'.$uid)) { // êàìïàíèÿ-èñòî÷íèê ñîîòâ-åò ïî òèïó è UID
                // â òğıêåğå åé ìîæåò ñîîòâ-òü íåñêîëüêî êàìïàíèé èñòî÷íèêîâ
                $aSrcData[$src_type][$uid][$src_id] = $campaign;
            }
        }
        foreach($aTrkData as $src_type => $trkData) {
            foreach($trkData as $uid => $url) {
                $stats = $this->VoluumApi->getCampaignDetailedList($url);
                foreach($stats as $row) {
                    if ($campaign = Hash::get($aSrcData, $src_type.'.'.$uid.'.'.$row['src_id'])) {
                        $profit = floatval($row['revenue']) - $campaign['cost'];
                        $data = array(
                            // äîï.äàííûå äëÿ ñîõğàíåíèÿ â ñòàòèñòèêó
                            'campaign_id' => $campaign['id'],
                            'src_visits' => $campaign['src_visits'],
                            'src_clicks' => $campaign['src_clicks'],
                            'cost' => $campaign['cost'],
                            'src_data' => $campaign['src_data'],

                            // äàííûå èç òğıêåğà - â êàìïàíèş è ñòàòèñòèêó
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

                        $data['id'] = $campaign['id']; // äëÿ ñîõğàíåíèÿ â íóæíóş êàìïàíèş
                        $this->Campaign->clear();
                        $this->Campaign->save($data);
                    }
                }
            }
        }
    }
}
