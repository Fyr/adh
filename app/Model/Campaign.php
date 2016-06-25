<?
App::uses('AppModel', 'Model');
App::uses('PlugrushApi', 'Model');
class Campaign extends AppModel {
	public $useTable = false;

	public function getList() {
		$aData = $this->loadModel('PlugrushApi')->getCampaignList();
		$aResult = array();
		foreach($aData as $data) {
			$aResult[$data['id']] = array('Campaign' => $data);
		}
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
			$aData = $this->loadModel('PlugrushApi')->getAdvertiserStats($startDate, $endDate, null, $campaignIds);
			$aData = array_reverse($aData);
			$aData = array_map(array($this, '_processStats'), $aData);

			foreach($aData as $data) {
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

	public function _processStats($e) {
		// ??? почему-то есть глюк - последний элемент для raw и uniques выдает как строку
		$e['date'] = date('d.m.Y', strtotime($e['date']));
		$e['uniques'] = intval($e['uniques']);
		$e['raws'] = intval($e['raws']);
		$e['amount'] = floatval($e['amount']);
		return $e;
	}
}
