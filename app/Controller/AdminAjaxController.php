<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class AdminAjaxController extends PAjaxController {
	public $name = 'Ajax';
	public $components = array('Core.PCAuth');

	public function getStats() {
		$campaign_ids = explode(',', $this->request->query('campaign_id'));
		try {
			$stats = $this->loadModel('Campaign')->getStats('2016-06-01', date('Y-m-d', time() + DAY), $campaign_ids);
			$this->setResponse($stats);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
}
