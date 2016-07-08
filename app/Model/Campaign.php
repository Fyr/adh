<?
App::uses('AppModel', 'Model');
App::uses('PlugrushApi', 'Model');
App::uses('VoluumApi', 'Model');
class Campaign extends AppModel {
	public $useTable = false;

	public function getList() {
		$aData = $this->loadModel('PlugrushApi')->getCampaignList();
		$aPlugRushData = Hash::combine($aData, '{n}.id', '{n}');

		$this->VoluumApi = $this->loadModel('VoluumApi');
		$aTrackerCampaigns = $this->VoluumApi->getTrackerCampaignList();
		$aResult = array();
		foreach($aTrackerCampaigns as $data) {
			$src = strtolower($data['trafficSource']);
			$trkData = array(
				'created' => date('Y-m-d H:i:s', strtotime($data['created'])),
				'campaign_id' => $data['campaignId'],
				'campaign_name' => $data['campaignName'],
				'country' => $data['campaignCountry'],
				'src_type' => $src, // PlugRush
				'src_title' => Configure::read($src.'.title'),
				'redirect_type' => ucfirst(strtolower($data['clickRedirectType'])),
				'cost_model' => $data['costModel']
			);

			if (in_array($src, array('plugrush'))) { // пока можем обработать только PlugRush
				$aSrcCampaignStats = $this->VoluumApi->getCampaignDetailedList($trkData['campaign_id']);
				foreach ($aSrcCampaignStats as $row) {
					$srcCampaignId = $row['src_campaign_id'];
					// выбираем нужные данные из всей строки
					$data = array(
						'src_campaign_id' => $row['src_campaign_id'],
						'visits' => intval($row['visits']),
						'clicks' => intval($row['clicks']),
						'conversion' => intval($row['conversions']),
						'revenue' => floatval($row['revenue']), // $0.00
						'cost' => floatval($row['cost']), // $0.00
						'profit' => floatval($row['profit']), // $0.00
						'cpv' => floatval($row['cpv']), // $0.0000
						'ctr' => floatval($row['ctr']), // 0.00%
						'roi' => floatval($row['roi']), // +-100.00%
					);
					// Добавляем инфу об кампании-источнике
					if (isset($aPlugRushData[$srcCampaignId])) { // на всякий случай еще раз проверяем
						$aResult[] = array(
							'Tracker' => $trkData,
							'Campaign' => $aPlugRushData[$srcCampaignId], // в зав-ти от trk source выбрать данные из нужного источника траффика
							'TrackerStats' => $data
						);
					}
				}
			}
		}
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
		return $aDomains;
	}
}
