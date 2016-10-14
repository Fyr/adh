<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
App::uses('Campaign', 'Model');

class AdminAjaxController extends PAjaxController {
	public $name = 'Ajax';
	public $components = array('Core.PCAuth');

	public function getStats() {
		try {
			$ids = $this->request->data('ids');
			if (!($ids & is_array($ids))) {
				throw new Exception('Incorrect request parameter `ids`');
			}
			$from = $this->request->data('from');
			$to = $this->request->data('to');
			if (!$from) {
				throw new Exception('Incorrect request parameter `from`');
			}

			$this->DailyCampaignStats = $this->loadModel('DailyCampaignStats');
			$stats = $this->DailyCampaignStats->getSummaryStats($ids, $from, $to);

			$this->DomainStats = $this->loadModel('DomainStats');
			$domainStats = $this->DomainStats->getTotalStats($ids, $from, $to);

			$domains = $this->loadModel('Domain')->getOptions(array_keys($domainStats));
			$this->setResponse(compact('stats', 'domainStats', 'domains'));


		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
}
