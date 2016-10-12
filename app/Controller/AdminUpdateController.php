<?php
App::uses('AdminController', 'Controller');
App::uses('Settings', 'Model');
class AdminUpdateController extends AdminController {
    public $name = 'AdminUpdate';

    public function beforeFilter() {
        $this->autoRender = false;
    }

    public function up1() {
        // Проинициализировать поле created по первой записи для кампании
        $this->Campaign = $this->loadModel('Campaign');
        $this->CampaignStats = $this->loadModel('CampaignStats');
        $aRowset = $this->Campaign->find('list');
        foreach($aRowset as $campaign_id => $_id) {
            $conditions = compact('campaign_id');
            $stats = $this->CampaignStats->find('first', compact('conditions'));
            $this->Campaign->clear();
            if ($stats) {
                $this->Campaign->save(array('id' => $campaign_id, 'created' => $stats['CampaignStats']['created']));
            } else {
                $this->Campaign->save(array('id' => $campaign_id, 'created' => '2016-09-21'));
            }
        }
    }

    public function up2() {
        ignore_user_abort(true);
        set_time_limit(0);

        // просчитать статистику кампаний по всем дням
        $this->Campaign = $this->loadModel('Campaign');
        $aRowset = $this->Campaign->find('all');
        $today = strtotime(date('Y-m-d 00:00:00'));
        foreach($aRowset as $row) {
            $created = strtotime($row['Campaign']['created']);
            $campaign_id = $row['Campaign']['id'];
            for($date = $created; $date < $today; $date+= DAY) {
                $_date = date('Y-m-d', $date);
                $this->runBkg("BkgService dailyStats {$_date} {$campaign_id}");
                echo "BkgService dailyStats {$_date} {$campaign_id} <br />";
                sleep(1);
                // fdebug("../Console/cake.bat BkgService dailyStats {$_date} {$campaign_id}\r\n");
            }
        }
    }
}
