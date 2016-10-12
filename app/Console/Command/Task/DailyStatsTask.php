<?php
App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');

App::uses('Settings', 'Model');
App::uses('Task', 'Model');
App::uses('Campaign', 'Model');
App::uses('CampaignStats', 'Model');
class DailyStatsTask extends AppShell {
    public $uses = array('Task', 'Campaign', 'CampaignStats', 'DailyCampaignStats');

    public function execute() {
        $stat_date = $this->params['stat_date'];
        $campaign_ids = $this->params['campaign_ids'];

        $this->Task->setProgress($this->id, 0, count($this->params['campaign_ids']));
        $this->Task->setStatus($this->id, Task::RUN);
        $i = 0;
        foreach($campaign_ids as $campaign_id) {
            if ($this->Task->getStatus($this->id) == Task::ABORT) {
                throw new Exception(__('Processing was aborted by user'));
            }

            $this->_processCampaign($campaign_id, $stat_date);

            $this->Task->setProgress($this->id, ++$i);
            $this->Task->saveStatus($this->id);
        }

        $this->Task->setStatus($this->id, Task::DONE);
    }

    private function _processCampaign($campaign_id, $stat_date) {
        $conditions = array('campaign_id' => $campaign_id, 'DATE(created)' => $stat_date);
        $order = 'id DESC';
        $limit = 1;
        // получаем последнюю запись - итоги на конец текущего дня
        $stats = $this->CampaignStats->find('first', compact('conditions', 'order', 'limit'));
        if (!$stats) {
            return; // нет данных - не обрабатываем
        }
        $stats = $stats['CampaignStats'];

        // получаем пред.запись за вчера - это итоги на начало текущего дня
        $conditions = array('campaign_id' => $campaign_id, 'DATE(created) < ' => $stat_date);
        $prevStats = $this->CampaignStats->find('first', compact('conditions', 'order', 'limit'));
        if ($prevStats) {
            $prevStats = $prevStats['CampaignStats'];
        } else {
            $fields = array(
                'src_visits', 'trk_visits', 'src_clicks', 'trk_clicks',
                'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv', 'trk_epv'
            );
            foreach($fields as $key) {
                $prevStats[$key] = 0;
            }
        }
        $field = array('src_visits', 'trk_visits', 'src_clicks', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit');
        $data = compact('stat_date', 'campaign_id');
        foreach($field as $key) {
            $data[$key] = $stats[$key];
            $data['d_'.$key] = $stats[$key] - $prevStats[$key];
        }

        $data['d_cpv'] = ($data['d_src_visits']) ? round($data['d_cost'] / $data['d_src_visits'], 4) : 0; // $0.0000
        $data['d_ctr'] = ($data['d_src_visits']) ? round($data['d_trk_clicks'] / $data['d_src_visits'] * 100) : 0; // 0.00%
        $data['d_roi'] = ($data['d_cost']) ? round($data['d_profit'] / $data['d_cost'] * 100) : 0;
        $data['d_epv'] = ($data['d_src_visits']) ? round($data['d_revenue'] / $data['d_src_visits'], 4) : 0;
        $data['d_trk_epv'] = ($data['d_trk_visits']) ? round($data['d_revenue'] / $data['d_trk_visits'], 4) : 0;

        $this->DailyCampaignStats->clear();
        $this->DailyCampaignStats->save($data);
    }
}
