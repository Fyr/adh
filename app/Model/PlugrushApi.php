<?
App::uses('AppModel', 'Model');
App::uses('Curl', 'Vendor');
class PlugrushApi extends AppModel {
	public $useTable = false;

	private function _sendRequest($method, $data = array()) {
		$data = am(array(
			'user' => Configure::read('Settings.plugrush_email'),
			'api_key' => Configure::read('Settings.plugrush_apikey'),
			'action' => $method
		), $data);
		$url = Configure::read('plugrush.api').'?'.http_build_query($data);
		$cacheKey = 'plugrush_'.md5($url);
		$curl = new Curl($url);

		$this->_writeLog(Configure::read('plugrush.log'), 'REQUEST', 'URL: '.$url);

		$response = Cache::read($cacheKey, 'api');
		if ($response) {
			$this->_writeLog(Configure::read('plugrush.log'), 'CACHE', $response);
			return $response['data'];
		}

		$response = $curl->setOption(CURLOPT_SSL_VERIFYPEER, false)->sendRequest();
		$this->_writeLog(Configure::read('plugrush.log'), 'RESPONSE', $response);

		if (!trim($response)) {
			throw new Exception('PlugRush API Error: No response from server');
		}

		$response = json_decode($response, true);
		if (!$response || !is_array($response)) {
			throw new Exception('PlugRush API Error: Bad response from server');
		}

		if (count($response) == 1 && isset($response['error']) && $response['error']) {
			throw new Exception('PlugRush API Error: '.$response['error']);
		}

		if (count($response) == 1 && isset($response['message']) && $response['message']) {
			throw new Exception('PlugRush API Error: '.$response['message']);
		}

		if (!isset($response['data'])) {
			throw new Exception('PlugRush API Error: No data from server');
		}

		Cache::write($cacheKey, $response, 'api');
		return $response['data'];
	}

	public function getCampaignList() {
		return $this->_sendRequest('campaign/list');
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
