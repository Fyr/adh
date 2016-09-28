<?
App::uses('AppModel', 'Model');
class CampaignStats extends AppModel {

    public function getStats($campaign_ids, $from = '', $to = '') {
        $conditions = array('campaign_id' => $campaign_ids);
        if ($from) {
            $from = (is_numeric($from)) ? $from : strtotime($from);
            $conditions['created > '] = date('Y-m-d 00:00:00', $from);
        }

        if ($to) {
            $to = (is_numeric($to)) ? $to : strtotime($to);
            $conditions['created < '] = date('Y-m-d 23:59:59', $to);
        }

        $fields = array(
            'id', 'created', 'campaign_id', 'src_visits', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv'
        );
        $order = 'id';
        $aStats = $this->find('all', compact('fields', 'conditions', 'order'));
        $aStats = Hash::combine($aStats, '{n}.CampaignStats.id', '{n}.CampaignStats', '{n}.CampaignStats.campaign_id');
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
        $aStats = $this->getStats($campaign_ids, $from, $to);
        foreach($aStats as $campaign_id => $stats) {
            foreach($stats as $id => $stat) {
                $date = date('Y-m-d H:00', strtotime($stat['created']));
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
        if ($from) {
            $from = (is_numeric($from)) ? $from : strtotime($from);
            $from = strtotime(date('Y-m-d 00:00:00', $from));
        }

        if ($to) {
            $to = (is_numeric($to)) ? $to : strtotime($to);
            $to = strtotime(date('Y-m-d 23:59:59', $to));
            $to = min($to, strtotime(date('Y-m-d H:59:59')));
        } else {
            $to = strtotime(date('Y-m-d H:59:59'));
        }

        $fields = array('src_visits', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv');
        $aTotal = array();
        foreach($campaign_ids as $campaign_id) {
            $aLastData = array();
            foreach($fields as $key) { // обнуляем массив
                $aLastData[$key] = 0;
            }
            for ($date = $from; $date <= $to; $date += HOUR) {
                $_date = date('Y-m-d H:i', $date);
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
                foreach(array('src_visits', 'trk_clicks', 'conversion', 'revenue', 'cost', 'profit') as $key) {
                    $aTotal[$_date][$key]+= $aCurrData[$key];
                }
            }
        }
        foreach($aTotal as $date => $stats) {
            $aTotal[$date]['cpv'] = ($stats['src_visits']) ? round($stats['cost'] / $stats['src_visits'], 4) : 0; // $0.0000
            $aTotal[$date]['ctr'] = ($stats['src_visits']) ? round($stats['trk_clicks'] / $stats['src_visits'] * 100) : 0; // 0.00%
            $aTotal[$date]['roi'] = ($stats['cost']) ? round($stats['profit'] / $stats['cost'] * 100) : 0;
            $aTotal[$date]['epv'] = ($stats['src_visits']) ? round($stats['revenue'] / $stats['src_visits'], 4) : 0;
        }
        $aTotalDates = array();
        foreach($aTotal as $date => $stats) {
            list($_date, $hour) = explode(' ', $date);
            if (!isset($aTotalDates[$_date]) && $hour == '23:00') { // берем данные на конец дня
                $aTotalDates[$_date] = array();
                foreach($fields as $key) { // обнуляем массив
                    $aTotalDates[$_date][$key] = 0;
                }
                foreach($fields as $key) {
                    $aTotalDates[$_date][$key] = $stats[$key]; // не суммируем т.к. статистика уже накопительная!!!
                }
            }
        }

        return array('byHours' => $aTotal, 'byDates' => $aTotalDates);
    }
}
