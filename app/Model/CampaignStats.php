<?
App::uses('AppModel', 'Model');
class CampaignStats extends AppModel {

    private function _adjustDatetimeRange($from = 0, $to = 0) {
        if ($from) {
            $from = (is_numeric($from)) ? $from : strtotime($from);
            $from = strtotime(date('Y-m-d 00:00:00', $from));
        }

        if ($to) {
            $to = (is_numeric($to)) ? $to : strtotime($to);
            $to = strtotime(date('Y-m-d 23:59:59', $to));
        }
        return compact('from', 'to');
    }

    public function getStats($campaign_ids, $from = '', $to = '') {
        $conditions = array('campaign_id' => $campaign_ids);
        $range = $this->_adjustDatetimeRange($from, $to);
        if ($range['from']) {
            $conditions['created >= '] = date('Y-m-d H:i:s', $range['from']);
        }
        if ($range['to']) {
            $conditions['created <= '] = date('Y-m-d H:i:s', $range['to']);
        }
        $fields = array(
            'id', 'created', 'campaign_id', 'src_visits', 'trk_visits', 'src_clicks', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv', 'trk_epv'
        );
        $order = 'id';
        $aStats = $this->find('all', compact('fields', 'conditions', 'order'));
        $aStats = Hash::combine($aStats, '{n}.CampaignStats.id', '{n}.CampaignStats', '{n}.CampaignStats.campaign_id');
        return $aStats;
    }

    public function _getStatsByDays($campaign_ids, $from = '', $to = '') {
        $aStats = $this->getStats($campaign_ids, $from, $to);
        foreach($aStats as $campaign_id => $stats) {
            foreach($stats as $id => $stat) {
                $date = date('Y-m-d', strtotime($stat['created']));
                if (!isset($aStats[$campaign_id]['stats'])) {
                    $aStats[$campaign_id]['stats'] = array();
                }
                if (!isset($aStats[$campaign_id]['stats'][$date])) {
                    $aStats[$campaign_id]['stats'][$date] = array();
                }
                $aStats[$campaign_id]['stats'][$date][] = $stat;
                unset($aStats[$campaign_id][$id]);
            }
        }
        return $aStats;
    }

    public function getSummaryStats($campaign_ids, $from = '', $to = '') {
        /*
         * Алг-тм подсчета суммарной статистики:
         * 1. Собираем все данные
         * 2. Группируем записи по часам - для точности (можно просто по дням, будет менее точнее)
         * 3. Отбираем первую запись из группы, т.к. может быть несколько записей за 1 час (напр. запустили вручную)
         *    Нельзя просто выбрать данные в NN:00, т.к. последние данные могут придти в NN:01
         * 4. По этой записи берем все стат.данные, остальные - заново вычисляем
         *    Нельзя брать просто средние значения, т.к. для этих показателей могут быть разные веса
         * 5. Если данных нету ДО нужного часа (нужнoй даты), то считаем что данные нулевые
         *    (скорее всего кампания еще не началась, вряд ли нет данных с нужной даты из-за ошибок API)
         * 6. Если данных нет ПОСЛЕ нужного часа, мы используем последние актуальные данные
         */
        $aStats = $this->_getStatsByDays($campaign_ids, $from, $to);
        fdebug($aStats, 'tmp1.log');
        $range = $this->_adjustDatetimeRange($from, $to);
        $fields = array('src_visits', 'trk_visits', 'src_clicks', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv', 'trk_epv');
        $aTotal = array();
        foreach($campaign_ids as $campaign_id) {
            $aLastData = array();
            foreach($fields as $key) { // обнуляем массив
                $aLastData[$key] = 0;
            }
            for ($date = $range['from']; $date <= $range['to']; $date += DAY) {
                $_date = date('Y-m-d', $date);
                if (isset($aStats[$campaign_id]) && isset($aStats[$campaign_id]['stats'])
                        && isset($aStats[$campaign_id]['stats'][$_date])) {
                    $aCurrData = $aStats[$campaign_id]['stats'][$_date][0];
                    $len = count($aStats[$campaign_id]['stats'][$_date]) - 1;
                    $aLastData = $aStats[$campaign_id]['stats'][$_date][$len];
                } else {
                    $aCurrData = $aLastData;
                }

                if (!isset($aTotal[$_date])) {
                    $aTotal[$_date] = array();
                    foreach($fields as $key) { // обнуляем массив
                        $aTotal[$_date][$key] = 0;
                    }
                }
                foreach(array('src_visits', 'trk_visits', 'src_clicks', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit') as $key) {
                    $aTotal[$_date][$key]+= $aCurrData[$key]; // сразу складываем статистику по всем выбранным кампаниям за день
                }
            }
        }
        foreach($aTotal as $date => $stats) {
            $aTotal[$date]['cpv'] = ($stats['src_visits']) ? round($stats['cost'] / $stats['src_visits'], 4) : 0; // $0.0000
            $aTotal[$date]['ctr'] = ($stats['src_visits']) ? round($stats['trk_clicks'] / $stats['src_visits'] * 100) : 0; // 0.00%
            $aTotal[$date]['roi'] = ($stats['cost']) ? round($stats['profit'] / $stats['cost'] * 100) : 0;
            $aTotal[$date]['epv'] = ($stats['src_visits']) ? round($stats['revenue'] / $stats['src_visits'], 4) : 0;
            $aTotal[$date]['trk_epv'] = ($stats['trk_visits']) ? round($stats['revenue'] / $stats['trk_visits'], 4) : 0;
        }
        return $aTotal;
    }
}
