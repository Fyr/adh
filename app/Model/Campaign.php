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
			$trkData = array(
				'created' => date('Y-m-d H:i:s', strtotime($data['created'])),
				'campaign_id' => $data['campaignId'],
				'campaign_name' => $data['campaignName'],
				'country' => $data['campaignCountry'],
				'source' => strtolower($data['trafficSource']), // PlugRush
			);

			if (in_array($trkData['source'], array('plugrush'))) { // пока можем обработать только PlugRush
				$aSrcCampaignStats = $this->VoluumApi->getCampaignDetailedList($trkData['campaign_id']);
				foreach ($aSrcCampaignStats as $row) {
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
					$srcCampaignId = $data['src_campaign_id'];
					$aResult[] = array(
						'Tracker' => $trkData,
						'Campaign' => $aPlugRushData[$srcCampaignId], // в зав-ти от trk source выбрать данные из нужного источника траффика
						'TrackerStats' => $data
					);
				}
			}
		}
		/*
		foreach($aPlugRushData as $data) {
			$aResult[$data['id']] = array('Campaign' => $data);
		}
		*/
		return $aResult;
	}

	/**
	 * @param string $startDate
	 * @param string $endDate
	 * @param mixed $campaign_id - can be int or array
	 * @return array
	 */
	public function getStats($startDate = '', $endDate = '', $campaignIds = null) {
		// $campaign_id = (is_array($campaign_id)) ? implode(',', $campaign_id): $campaign_id;
		if (!is_array($campaignIds)) {
			$campaignIds = array($campaignIds);
		}
		$aTotal = array();
		foreach($campaignIds as $id) {
			$aData = $this->loadModel('PlugrushApi')->getAdvertiserStats($startDate, $endDate, null, $id);
			$aData = array_reverse($aData);
			// $aData = array_map(array($this, '_processStats'), $aData);

			foreach($aData as $data) {
				// почему-то есть глюк - последний элемент для raw и uniques выдает как строку
				$data['date'] = date('d.m.Y', strtotime($data['date']));
				$data['uniques'] = intval($data['uniques']);
				$data['raws'] = intval($data['raws']);
				$data['amount'] = floatval($data['amount']);
				
				$date = $data['date'];
				if (!isset($aTotal[$date])) {
					$aTotal[$date] = array('uniques' => 0, 'raws'=> 0, 'amount' => 0);
				}
				foreach(array('uniques', 'raws', 'amount') as $field) {
					$aTotal[$date][$field]+= $data[$field];
				}
			}
		}

		return $aTotal;
	}
}
