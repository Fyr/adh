<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class AdminAjaxController extends PAjaxController {
	public $name = 'Ajax';
	public $components = array('Core.PCAuth');

	public function getStats() {
		$campaign_ids = explode(',', $this->request->query('campaign_id'));
		$stats = $this->loadModel('Campaign')->getStats(null, null, $campaign_ids);
		$this->setResponse($stats);
	}
}
