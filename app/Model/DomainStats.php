<?
App::uses('AppModel', 'Model');
class DomainStats extends AppModel {

    public function getTotalStats($campaign_ids, $from = '', $to = '') {
        $conditions = array('campaign_id' => $campaign_ids);
        if ($from) {
            $from = (is_numeric($from)) ? $from : strtotime($from);
            $from = strtotime(date('Y-m-d 00:00:00', $from));
            $conditions['created > '] = date('Y-m-d 00:00:00', $from);
        }

        if ($to) {
            $to = (is_numeric($to)) ? $to : strtotime($to);
            $to = strtotime(date('Y-m-d 23:59:59', $to));
            $to = min($to, strtotime(date('Y-m-d H:59:59')));
            $conditions['created < '] = date('Y-m-d 23:59:59', $to);
        } else {
            $to = strtotime(date('Y-m-d H:59:59'));
        }
        $order = 'created DESC';
        $fields = array(
            'id', 'created', 'campaign_id', 'domain_id',
            'src_visits', 'trk_visits', 'src_clicks', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit', 'is_trk_data'
        );
        $aStats = $this->find('all', compact('fields', 'conditions', 'order'));
        $aStats = Hash::combine($aStats, '{n}.DomainStats.id', '{n}.DomainStats', '{n}.DomainStats.domain_id');
        foreach($aStats as $domain_id => $stats) {
            $campaignStats = Hash::combine($stats, '{n}.id', '{n}', '{n}.campaign_id');
            $stats = array();
            foreach($campaignStats as $campaign_id => $row) {
                $stats[$campaign_id] = array_shift($row);
            }
            $aStats[$domain_id] = $stats;
        }

        
        foreach($aStats as $domain_id => $stats) {
            $total = array('is_trk_data' => true);
            // обнуляем массив
            foreach(array('src_visits', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit') as $key) {
                $total[$key] = 0;
            }
            // суммируем статистику по кампаниям
            foreach($stats as $campaign_id => $_stats) {
                foreach (array('src_visits', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit') as $key) {
                    $total[$key] += $_stats[$key];
                }
                if (!$_stats['is_trk_data']) {
                    $total['is_trk_data'] = false;
                }
            }

            $total['cpv'] = ($total['src_visits']) ? round($total['cost'] / $total['src_visits'], 4) : 0; // $0.0000
            $total['ctr'] = ($total['src_visits']) ? round($total['trk_clicks'] / $total['src_visits'] * 100) : 0; // 0.00%
            $total['roi'] = ($total['cost']) ? round($total['profit'] / $total['cost'] * 100) : 0;
            $total['epv'] = ($total['src_visits']) ? round($total['revenue'] / $total['src_visits'], 4) : 0;

            $aStats[$domain_id] = $total;
        }
        return $aStats;
    }
}
