<?
App::uses('AppModel', 'Model');
App::uses('Curl', 'Vendor');
class PopadsApi extends AppModel {
	public $useTable = false;

	private function _sendRequest($method, $data = array()) {
		$data = am(array(
			'key' => Configure::read('Settings.popads_apikey'),
		), $data);
		$url = Configure::read('popads.api').'/'.$method.'?'.http_build_query($data);
		$cacheKey = 'popads_'.md5($url);
		$curl = new Curl($url);

		$this->_writeLog(Configure::read('popads.log'), 'REQUEST', 'URL: '.$url);

		$response = Cache::read($cacheKey, 'api');
		if ($response) {
			$this->_writeLog(Configure::read('popads.log'), 'CACHE', $response);
			return $response;
		}

		$response = $curl->setOption(CURLOPT_SSL_VERIFYPEER, false)->sendRequest();
		$this->_writeLog(Configure::read('popads.log'), 'RESPONSE', $response);

		if (!trim($response)) {
			throw new Exception('PopAds API Error: No response from server');
		}

		$response = json_decode($response, true);
		if (!$response || !is_array($response)) {
			throw new Exception('PopAds API Error: Bad response from server');
		}

		if (isset($response['errors']) && isset($response['errors'][0]) && isset($response['errors'][0]['title'])) {
			throw new Exception('PopAds API Error: '.$response['errors'][0]['title']);
		}

		Cache::write($cacheKey, $response, 'api');
		return $response;
	}

	public function getCampaignList() {
		$response = $this->_sendRequest('campaign_list');
		if (!isset($response['campaigns'])) {
			throw new Exception('PopAds API Error: No campaign data');
		}
		return $response['campaigns'];
	}

	private function _parseDatetime($time) {
		return date('Y-m-d', $time);
	}

	public function getTrafficStats($campaign) {
		$start = $this->_parseDatetime(Configure::read('date.from'));
		$end = $this->_parseDatetime(Configure::read('date.to'));
		$breakdown = 'dates';
		return $this->_sendRequest('advertiser/stats', compact('start', 'end', 'breakdown', 'campaign'));
	}

	public function getDomainStats($campaign) {
		$start = $this->_parseDatetime(Configure::read('date.from'));
		$end = $this->_parseDatetime(Configure::read('date.to'));
		$breakdown = 'referrals';
		return $this->_sendRequest('advertiser/stats', compact('start', 'end', 'breakdown', 'campaign'));
	}
}
