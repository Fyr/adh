<?
App::uses('AppModel', 'Model');
App::uses('Curl', 'Vendor');
class PlugrushApi extends AppModel {
	public $useTable = false;

	private function _writeLog($actionType, $data = '') {
		$string = date('d-m-Y H:i:s') . ' ' . $actionType . ' ' . $data;
		file_put_contents(Configure::read('plugrush.log'), $string . "\r\n", FILE_APPEND);
	}


	private function _sendRequest($method, $data = array()) {
		$data = am(array(
			'user' => Configure::read('Settings.plugrush_email'),
			'api_key' => Configure::read('Settings.plugrush_apikey'),
			'action' => $method
		), $data);
		$url = Configure::read('plugrush.api').'?'.http_build_query($data);
		$curl = new Curl($url);

		if (Configure::read('plugrush.log')) {
			$this->_writeLog('REQUEST', 'URL: '.$url);
		}

		if (TEST_ENV) {
			if ($method == 'advertiser/stats') {
				$response = '{"data":[{"date":"2016-06-23","amount":0.3045,"uniques":"1218","raws":"1218"},{"date":"2016-06-23","amount":0.0313,"uniques":125,"raws":125},{"date":"2016-06-22","amount":0.1462,"uniques":585,"raws":585},{"date":"2016-06-21","amount":0.0762,"uniques":305,"raws":305},{"date":"2016-06-20","amount":0.0438,"uniques":175,"raws":175},{"date":"2016-06-19","amount":0.0485,"uniques":194,"raws":194},{"date":"2016-06-18","amount":0.0687,"uniques":275,"raws":275},{"date":"2016-06-17","amount":0.0682,"uniques":273,"raws":273},{"date":"2016-06-16","amount":0.5395,"uniques":676,"raws":676}]}';
			} elseif ($method == 'campaign/list') {
				$response = '{"data":[{"id":"7601544","created":"2016-06-14 04:35:49","title":"TR iOS Red","type":"mobile_redirect","status":"completed","url":"http:\/\/mv7tv.voluumtrk.com\/9b7c4cc9-2d9f-4d8f-a62d-2e82c2c76d16?id={$id}&cc={$cc}&category={$category}&domain={$domain}&source={$trafficsource}&orientation={$orientation}&campaign_id={$campaign_id}&banner_id={$banner_id}&ad_id={$ad_id}&ts=plugrush","plugs_count":"0","countries_count":"1","traffic_ordered":"10000","traffic_received":"10010","traffic_ordered_current_period":10000,"traffic_received_current_period":10010,"traffic_received_current_hour":"8","max_hits_per_hour":"10000","bid":0.001,"paid":10,"spent":10.01,"autorenew":"no","started":"2016-06-14 04:34:00","ended":"2016-06-16"},{"id":"7601342","created":"2016-06-12 14:33:31","title":"TR Android Red","type":"mobile_redirect","status":"completed","url":"http:\/\/mv7tv.voluumtrk.com\/9b7c4cc9-2d9f-4d8f-a62d-2e82c2c76d16?id={$id}&cc={$cc}&category={$category}&domain={$domain}&source={$trafficsource}&orientation={$orientation}&campaign_id={$campaign_id}&banner_id={$banner_id}&ad_id={$ad_id}&ts=plugrush","plugs_count":"0","countries_count":"1","traffic_ordered":"14484","traffic_received":"14527","traffic_ordered_current_period":9999,"traffic_received_current_period":10042,"traffic_received_current_hour":"40","max_hits_per_hour":"10000","bid":0.001,"paid":20,"spent":20.04,"autorenew":"no","started":"2016-06-12 14:29:00","ended":"2016-06-15"},{"id":"7601546","created":"2016-06-14 04:37:14","title":"TR Android Pop","type":"mobile_pop","status":"running","url":"http:\/\/mv7tv.voluumtrk.com\/9b7c4cc9-2d9f-4d8f-a62d-2e82c2c76d16?id={$id}&cc={$cc}&category={$category}&domain={$domain}&source={$trafficsource}&orientation={$orientation}&campaign_id={$campaign_id}&banner_id={$banner_id}&ad_id={$ad_id}&ts=plugrush","plugs_count":"0","countries_count":"1","traffic_ordered":"40000","traffic_received":"3605","traffic_ordered_current_period":40000,"traffic_received_current_period":3605,"traffic_received_current_hour":"114","max_hits_per_hour":"5000","bid":0.00025,"paid":10,"spent":0.9,"autorenew":"no","started":"2016-06-14 04:35:00","ended":"still running"}]}';
			}
		} else {
			$response = $curl->setOption(CURLOPT_SSL_VERIFYPEER, false)->sendRequest();
		}
		if (Configure::read('plugrush.log')) {
			$this->_writeLog('RESPONSE', $response);
		}
		if (!trim($response)) {
			throw new Exception('PlugRush API: No response from server');
		}

		/*
		if (strpos($response, 'no data by this request')) {
			return array();
		}
		*/

		$response = json_decode($response, true);
		if (!$response || !is_array($response)) {
			throw new Exception('PlugRush API: Bad response from server');
		}
		if (count($response) == 1 && isset($response['message']) && $response['message']) {
			throw new Exception($response['error']);
		}

		if (!isset($response['data'])) {
			throw new Exception('PlugRush API: No data from server');
		}
		return $response['data'];
	}

	public function getCampaignList() {
		return $this->_sendRequest('campaign/list');
	}

	public function getAdvertiserStats($start = '', $end = '', $breakdown = 'dates', $campaign = null) {
		return $this->_sendRequest('advertiser/stats', compact('start', 'end', 'breakdown', 'campaign'));
	}
}
