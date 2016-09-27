<?
App::uses('AppModel', 'Model');
class Settings extends AppModel {

	public function initData() {
		$data = $this->getData();
		foreach($data['Settings'] as $key => $val) {
			Configure::write('Settings.'.$key, $val);
		}
	}
	
	public function getData() {
		return $this->find('first');
	}
/*
	public function adjustDateRange($from, $to) {
		Configure::write('date', array(
			'from' => strtotime($from),
			'to' => strtotime($to)
		));
	}
*/
}
