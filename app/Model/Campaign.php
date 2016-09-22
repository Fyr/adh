<?
App::uses('AppModel', 'Model');
App::uses('PlugrushApi', 'Model');
App::uses('PopadsApi', 'Model');
App::uses('VoluumApi', 'Model');
class Campaign extends AppModel {

	const TYPE_PLUGRUSH = 'plugrush';
	const TYPE_POPADS = 'popads';

	public function getSourceList() {
		$fields = array('id', 'src_id', 'src_type', 'src_name', 'url', 'active', 'status');
		$aResult = $this->find('all', compact('fields'));
		return Hash::extract($aResult, '{n}.Campaign');
	}

	public function getList($ids = null) {
		$conditions = array('id' => $ids);
		$order = 'created DESC';
		$aResult = $this->find('all', compact('conditions', 'order'));
		return $aResult;
	}

	/**
	 * Get info about traffic source (visits, clicks, cost etc)
	 * @param mixed $aID - assoc array('trk_id', 'src_type', 'src_id')
	 * @return array
	 */
	public function getTrafficStats($aID) {
		$this->VoluumApi = $this->loadModel('VoluumApi');
		$this->PlugrushApi = $this->loadModel('PlugrushApi');
		$aTotal = array();
		foreach($aID as $row) {
			// Собираем статитику по всем источникам траффика
			if ($row['src_type'] == 'plugrush') { // Статистика по траффику с PlugRush

				$aData = $this->PlugrushApi->getTrafficStats($row['src_id']);
				$aData = array_reverse($aData);
				foreach ($aData as $data) {
					// почему-то есть глюк - последний элемент для raw и uniques выдает как строку
					$data['date'] = date('d.m.Y', strtotime($data['date']));
					$data['uniques'] = intval($data['uniques']);
					$data['raws'] = intval($data['raws']);
					$data['amount'] = floatval($data['amount']);

					$date = $data['date'];
					if (!isset($aTotal[$date])) {
						$aTotal[$date] = array('uniques' => 0, 'raws' => 0, 'amount' => 0);
					}
					foreach (array('uniques', 'raws', 'amount') as $field) {
						$aTotal[$date][$field] += $data[$field];
					}
				}
			}
		}
		return $aTotal;
	}

	public function getDomainStats($aID) {
		$this->VoluumApi = $this->loadModel('VoluumApi');
		$this->PlugrushApi = $this->loadModel('PlugrushApi');
		$aDomains = array();
		// $trk_ids = array_unique(Hash::extract($aID, '{n}.trk_id'));
		foreach($aID as $_row) {
			// Статистика по доменам трэкера
			$aData = $this->VoluumApi->getDomainList($_row['trk_id'], $_row['src_id']);
			foreach ($aData as $row) {
				$domain = $row['domain'];
				if (!isset($aDomains[$domain])) {
					$aDomains[$domain] = array();
				}
				if (!isset($aDomains[$domain]['Tracker'])) {
					$aDomains[$domain]['Tracker'] = array(
						'visits' => 0,
						'clicks' => 0,
						'conversions' => 0,
						'revenue' => 0, // $0.00
						'cost' => 0, // $0.00
						'profit' => 0, // $0.00
						'cpv' => 0, // $0.0000
						'ctr' => 0, // 0.00% - ???
						'roi' => 0, // +-100.00% - ???
					);
				}

				$aDomains[$domain]['Tracker']['visits'] += intval($row['visits']);
				$aDomains[$domain]['Tracker']['clicks'] += intval($row['clicks']);
				$aDomains[$domain]['Tracker']['conversions'] += intval($row['conversions']);
				$aDomains[$domain]['Tracker']['revenue'] += floatval($row['revenue']); // $0.00
				$aDomains[$domain]['Tracker']['cost'] += floatval($row['cost']); // $0.00
				$aDomains[$domain]['Tracker']['profit'] += floatval($row['profit']); // $0.00
				$aDomains[$domain]['Tracker']['cpv'] += floatval($row['cpv']); // $0.0000
			}
		}

		// скрректировать % по суммам
		foreach($aDomains as $domain => $row) {
			$row = $row['Tracker'];
			$aDomains[$domain]['Tracker']['ctr'] = round(100 * $row['clicks'] / $row['visits'], 2); // %
			$aDomains[$domain]['Tracker']['roi'] = round(100 * ($row['revenue'] - $row['cost']) / $row['cost'], 4); // %
		}

		foreach($aID as $_row) {
			// Собираем статитику по всем источникам траффика в разрезе кампаний
			if ($_row['src_type'] == 'plugrush') {
				$aData = $this->PlugrushApi->getDomainStats($_row['src_id']);
				foreach($aData as $row) {
					$domain = $row['domain'];
					if (!isset($aDomains[$domain])) {
						$aDomains[$domain] = array();
					}
					if (!isset($aDomains[$domain]['Sources'])) {
						$aDomains[$domain]['Sources'] = array();
					}
					if (!isset($aDomains[$domain]['Sources'][$_row['src_type']])) {
						$aDomains[$domain]['Sources'][$_row['src_type']] = array(
							'domain_id' => intval($row['domain_id']),
							'uniques' => 0,
							'raws' => 0,
							'amount' => 0,
						);
					}

					$aDomains[$domain]['Sources'][$_row['src_type']]['uniques'] += intval($row['uniques']);
					$aDomains[$domain]['Sources'][$_row['src_type']]['raws'] += intval($row['raws']);
					$aDomains[$domain]['Sources'][$_row['src_type']]['amount'] += floatval($row['amount']);
				}
			}
		}

		// Для некоторых доменов нету данных по трэкеру
		// Возможно, что для некоторых доменов не будет даннных по каким-то источникам траффика

		// Преобразовываем к в обычный массив для domainsGrid - это нужно для облегчения сортировки
		foreach($aDomains as $domain => &$data) {
			$data['domain'] = $domain;
		}
		return array_values($aDomains);
	}
}

