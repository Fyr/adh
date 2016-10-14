<?
App::uses('AppModel', 'Model');
class DailyCampaignStats extends AppModel {

    /*
     * Возвращает статистику в виде массива дата[]=>кампания[]=>данные
     */
    public function getStats($campaign_ids, $from, $to = '') {
        $conditions = array('campaign_id' => $campaign_ids);
        $range = $this->_adjustDateRange($from, $to);
        if ($range['from']) {
            $conditions['stat_date >= '] = date('Y-m-d H:i:s', $range['from']);
        }
        if ($range['to']) {
            $conditions['stat_date <= '] = date('Y-m-d H:i:s', $range['to']);
        }
        $fields = array(
            'id', 'stat_date', 'campaign_id',
            'src_visits', 'trk_visits', 'src_clicks', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv', 'trk_epv',
            'd_src_visits', 'd_trk_visits', 'd_src_clicks', 'd_trk_clicks',
            'd_conversion', 'd_revenue', 'd_cost', 'd_profit', 'd_cpv', 'd_ctr', 'd_roi', 'd_epv', 'd_trk_epv'
        );
        $order = 'id';
        $aStats = $this->find('all', compact('fields', 'conditions', 'order'));
        $aStats = Hash::combine($aStats, '{n}.DailyCampaignStats.stat_date', '{n}.DailyCampaignStats', '{n}.DailyCampaignStats.campaign_id');
        return $aStats;
    }

    public function getSummaryStats($campaign_ids, $from, $to = '') {
        $stats = $this->getStats($campaign_ids, $from, $to);
        $range = $this->_adjustDateRange($from, $to);
        $fields = array(
            'src_visits', 'trk_visits', 'src_clicks', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit'
        );
        $aTotal = array();
        foreach($stats as $campaign_id => $stat) {
            // обнуляем массив
            $prev = array(); // содержит последние актуальные данные на день по кампании
            foreach ($fields as $key) {
                $prev[$key] = 0;
                $prev['d_'.$key] = 0;
            }
            for ($date = $range['from']; $date < $range['to']; $date += DAY) {
                $d = date('Y-m-d', $date);

                // Получить в $curr данные за текущий день
                if (isset($stats[$campaign_id][$d])) {
                    $curr = $prev = $stats[$campaign_id][$d];
                } else {
                    // обнуляем показатели за день
                    foreach ($fields as $key) {
                        $prev['d_'.$key] = 0;
                    }
                    $curr = $prev;
                }

                if (!isset($aTotal[$d])) {
                    $aTotal[$d] = array();
                    foreach($fields as $key) {
                        $aTotal[$d][$key] = 0;
                        $aTotal[$d]['d_'.$key] = 0;
                    }
                }

                foreach ($fields as $key) {
                    $aTotal[$d][$key]+= $curr[$key];
                }
                foreach ($fields as $key) {
                    $aTotal[$d]['d_' . $key] += $curr['d_' . $key];
                }
            }
        }

        // Перерасчет данных
        foreach($aTotal as $date => $stats) {
            foreach(array('', 'd_') as $d_) {
                $aTotal[$date][$d_.'cpv'] = ($stats[$d_.'src_visits']) ? round($stats[$d_.'cost'] / $stats[$d_.'src_visits'], 4) : 0; // $0.0000
                $aTotal[$date][$d_.'ctr'] = ($stats[$d_.'src_visits']) ? round($stats[$d_.'trk_clicks'] / $stats[$d_.'src_visits'] * 100) : 0; // 0.00%
                $aTotal[$date][$d_.'roi'] = ($stats[$d_.'cost']) ? round($stats[$d_.'profit'] / $stats[$d_.'cost'] * 100) : 0;
                $aTotal[$date][$d_.'epv'] = ($stats[$d_.'src_visits']) ? round($stats[$d_.'revenue'] / $stats[$d_.'src_visits'], 4) : 0;
                $aTotal[$date][$d_.'trk_epv'] = ($stats[$d_.'trk_visits']) ? round($stats[$d_.'revenue'] / $stats[$d_.'trk_visits'], 4) : 0;
            }
        }
        return $aTotal;
    }
}
