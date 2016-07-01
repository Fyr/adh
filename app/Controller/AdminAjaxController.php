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
			$aID = array();
			foreach($ids as $row) {
				list($trk_id, $src_type, $src_id) = explode(',', $row);
				$aID[] = compact('trk_id', 'src_type', 'src_id');
			}

			$this->Campaign = $this->loadModel('Campaign');
			$traffic = $this->Campaign->getTrafficStats($aID);
			$domains = $this->Campaign->getDomainStats($aID);
			$this->setResponse(compact('traffic', 'domains'));

		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
}
