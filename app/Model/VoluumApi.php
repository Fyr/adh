<?
App::uses('AppModel', 'Model');
App::uses('Curl', 'Vendor');
class VoluumApi extends AppModel {
	public $useTable = false;

	private function _getAuthToken() {
		$response = $this->_readTokenCache();
		$expired = ($response) ? strtotime(Hash::get($response, 'expirationTimestamp')) : 0;
		if (time() >= $expired) {
			$token = $this->_fetchAuthToken();
		} else {
			$token = Hash::get($response, 'token');
		}
		return $token;
	}

	private function _fetchAuthToken() {
		$url = Configure::read('voluum.token_api');
		$curl = new Curl($url);
		$auth = array(
			'Authorization: Basic '.base64_encode(Configure::read('Settings.voluum_email').':'.Configure::read('Settings.voluum_psw'))
		);
		$curl->setOption(CURLOPT_SSL_VERIFYPEER, false)
			->setOption(CURLOPT_HTTPHEADER, $auth);

		$this->_writeLog(Configure::read('voluum.log'), 'REQUEST', 'URL: '.$url.' Authorization: '.$auth[0]);
		$response = $curl->sendRequest();
		$this->_writeLog(Configure::read('voluum.log'), 'RESPONSE', $response);

		if (!trim($response)) {
			throw new Exception('Voluum Auth API: No response from server');
		}

		$response = json_decode($response, true);
		if (!$response || !is_array($response)) {
			throw new Exception('Voluum Auth API: Bad response from server');
		}

		if (isset($response['error'])) {
			throw new Exception(Hash::get($response, 'error.status').' '.Hash::get($response, 'error.typeName'));
		}

		if (!(isset($response['token']) && isset($response['expirationTimestamp']))) {
			throw new Exception('Voluum Auth API: Bad auth token response from server');
		}

		$this->_writeTokenCache($response);
		return Hash::get($response, 'token');
	}

	private function _writeTokenCache($response) {
		$cacheFile = Configure::read('voluum.token_cache');
		list($expired) = explode('.', $response['expirationTimestamp']); // выдает в некорректном формате ISO 8601 напр. 2016-06-29T01:07:22.531Z
		$response['expirationTimestamp'] = str_replace('T', ' ', $expired);
		file_put_contents($cacheFile, serialize($response), false);
	}

	private function _readTokenCache() {
		$cacheFile = Configure::read('voluum.token_cache');
		return (file_exists($cacheFile)) ? unserialize(file_get_contents($cacheFile)) : '';
	}

	public function sendRequest($data = array()) {
		$url = Configure::read('voluum.api').'?'.((is_array($data)) ? http_build_query($data) : $data); // groupBy=campaign&from=2016-06-28&to=2016-06-28';
		$curl = new Curl($url);
		$auth = array(
			'cwauth-token: '.$this->_getAuthToken()
		);
		$this->_writeLog(Configure::read('voluum.log'), 'REQUEST', 'URL: '.$url.' DATA: '.serialize($data));
		$response = $curl->setOption(CURLOPT_HTTPHEADER, $auth)
			->setOption(CURLOPT_SSL_VERIFYPEER, false)
			->sendRequest();
		$this->_writeLog(Configure::read('voluum.log'), 'RESPONSE', $response);

		if (!trim($response)) {
			throw new Exception('Voluum API: No response from server');
		}

		$response = json_decode($response, true);
		if (!$response || !is_array($response)) {
			throw new Exception('Voluum API: Bad response from server');
		}

		if (isset($response['error'])) {
			$errMsg = Hash::get($response, 'error.messages.0');
			if (!$errMsg) {
				$errMsg = Hash::get($response, 'error.description');
			}
			throw new Exception('Voluum API Error! '.Hash::get($response, 'error.code').': '.$errMsg);
		}

		return $response;
	}

	private function _parseDatetime($time) {
		// почему то не принимает не-нулевые часы и секунды
		// хотя принимает даты в обычном Y-m-d формате
		return date('Y-m-d', $time).'T00:00:00Z'; // '.date('H:i:s', $time).'
	}

	public function getTrackerCampaignList() {
		$from = $this->_parseDatetime(mktime(0, 0, 0, 1, 1, 2016));
		$now = $this->_parseDatetime(time() + DAY);
		// судя по моим тестам список кампаний не зависит от передаваемой даты
		$data = "groupBy=campaign&from={$from}&to={$now}";
		$aData = $this->sendRequest($data);
		return $aData['rows'];
	}

	public function getCampaignDetailedList($campaignId) {
		// Какая-то начальная дата, чтобы выгрести все данные
		// Чем более ранняя дата - тем дольше выполняется запрос
		$from = $this->_parseDatetime(mktime(0, 0, 0, 1, 1, 2016));
		$now = $this->_parseDatetime(time() + DAY); // т.к. часы скидываются, нужно брать на день вперед
		$data = "groupBy=custom-variable-7&include=active&filter1=campaign&filter1Value={$campaignId}&from={$from}&to={$now}";
		$response = $this->sendRequest($data);
		$aData = array();
		foreach($response['rows'] as $row) {
			// Игнорить ID кампаний типа "пусто" или "{campaign_id}"
			$srcCampaignId = intval($row['customVariable7']);
			if ($srcCampaignId) {
				$row['src_campaign_id'] = $srcCampaignId;
				$aData[] = $row;
			}
		}
		return $aData;
	}
}
