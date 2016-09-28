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

		// Для auth-токена свой собственный кэш
		$this->_writeTokenCache($response);
		return Hash::get($response, 'token');
	}

	private function _writeTokenCache($response) {
		$cacheFile = Configure::read('voluum.token_cache');
		list($expired) = explode('.', $response['expirationTimestamp']); // выдает в некорректном формате ISO 8601 напр. 2016-06-29T01:07:22.531Z
		$response['expirationTimestamp'] = date('Y-m-d H:i:s', time() + 30 * MINUTE); // str_replace('T', ' ', $expired); почему-то авторизация летает раньше при указанной даты
		file_put_contents($cacheFile, serialize($response), false);
	}

	private function _readTokenCache() {
		$cacheFile = Configure::read('voluum.token_cache');
		return (file_exists($cacheFile)) ? unserialize(file_get_contents($cacheFile)) : '';
	}

	public function sendRequest($data = array()) {
		$url = Configure::read('voluum.api').'?'.((is_array($data)) ? http_build_query($data) : $data);
		$cacheKey = 'voluum_'.md5($url);
		$curl = new Curl($url);
		$auth = array(
			'cwauth-token: '.$this->_getAuthToken()
		);
		$this->_writeLog(Configure::read('voluum.log'), 'REQUEST', 'URL: '.$url.' DATA: '.serialize($data));

		if ($cacheStorage = Configure::read('voluum.cache')) {
			$response = Cache::read($cacheKey, $cacheStorage);
			if ($response) {
				$this->_writeLog(Configure::read('voluum.log'), 'CACHE', $response);
				return $response;
			}
		}

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

		if ($cacheStorage = Configure::read('voluum.cache')) {
			Cache::write($cacheKey, $response, $cacheStorage);
		}
		return $response;
	}

	private function _parseDatetime($time) {
		// почему то не принимает не-нулевые часы и секунды
		// хотя принимает даты в обычном Y-m-d формате
		return date('Y-m-d', $time).'T00:00:00Z'; // '.date('H:i:s', $time).'
	}

	public function getTrackerCampaignList() {
		// забираем ВСЕ кампании
		$from = $this->_parseDatetime(strtotime('2016-09-01 00:00:00'));
		$to = $this->_parseDatetime(time() + DAY);
		// ? судя по моим тестам список кампаний не зависит от передаваемой даты
		$data = "groupBy=campaign&from={$from}&to={$to}";
		$aData = $this->sendRequest($data);
		return $aData['rows'];
	}

	public function getCampaignDetailedList($campaignURL) {
		// Какая-то начальная дата, чтобы выгрести все данные
		// Чем более ранняя дата - тем дольше выполняется запрос
		$from = $this->_parseDatetime(Configure::read('date.from'));
		$to = $this->_parseDatetime(Configure::read('date.to')); // т.к. часы скидываются, нужно брать на день вперед
		$campaign_id_var = $this->_getCampaignId($campaignURL);
		$campaignId = $this->getCampaignUID($campaignURL);
		$data = "groupBy=custom-variable-{$campaign_id_var}&include=active&filter1=campaign&filter1Value={$campaignId}&from={$from}&to={$to}";
		$response = $this->sendRequest($data);
		$aData = array();
		foreach($response['rows'] as $row) {
			// Игнорить ID кампаний типа "пусто" или "{campaign_id}"
			$srcCampaignId = intval($row['customVariable'.$campaign_id_var]);
			if ($srcCampaignId) {
				$row['src_id'] = $srcCampaignId;
				$aData[] = $row;
			}
		}
		return $aData;
	}

	public function getDomainList($campaignURL, $srcCampaignId = null) {
		/*
		 https://reports.voluum.com/report?from=2016-06-29T00:00:00Z&to=2016-06-30T00:00:00Z&tz=America%2FNew_York&sort=visits&direction=desc&columns=customVariable4&columns=visits&columns=clicks&columns=conversions&columns=revenue&columns=cost&columns=profit&columns=cpv&columns=ctr&columns=cr&columns=cv&columns=roi&columns=epv&columns=epc&columns=ap&columns=errors&groupBy=custom-variable-4&offset=0&limit=100&include=active&filter1=campaign&filter1Value=9b7c4cc9-2d9f-4d8f-a62d-2e82c2c76d16&filter2=custom-variable-7&filter2Value=7601546

		 */
		$from = $this->_parseDatetime(Configure::read('date.from'));
		$now = $this->_parseDatetime(Configure::read('date.to')); // т.к. часы скидываются, нужно брать на день вперед
		$campaignId = $this->getCampaignUID($campaignURL);
		$data = "groupBy=custom-variable-4&include=active&filter1=campaign&filter1Value={$campaignId}&from={$from}&to={$now}";
		if ($srcCampaignId) {
			$data.= '&filter2=custom-variable-7&filter2Value='.$srcCampaignId;
		}
		$response = $this->sendRequest($data);

		$aData = array();
		foreach($response['rows'] as $row) {
			$domain = trim($row['customVariable4']);
			if ($domain) { // игнорим пустые домены
				$row['domain'] = $domain;
				$aData[] = $row;
			}
		}
		return $aData;
	}

	private function _getCampaignId($url) {
		parse_str($url, $vars);
		$i = 0;
		// ищем переменную campaign_id или campaignid
		foreach($vars as $var => $val) {
			$i++;
			if (strtolower($var) == 'campaignid' || strtolower($var) == 'campaign_id') {
				return $i;
			}
		}

		// если ее нету - ищем campaign
		$i = 0;
		foreach($vars as $var => $val) {
			$i++;
			if (strtolower($var) == 'campaign') {
				return $i;
			}
		}
		return 0;
	}

	public function getCampaignUID($url) {
		return str_replace('/', '', parse_url($url, PHP_URL_PATH));
	}
}
