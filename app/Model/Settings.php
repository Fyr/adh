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

	public function adjustDateRange($from, $to) {
		Configure::write('date', array(
			'from' => strtotime($from),
			'to' => strtotime($to)
		));
		/*
		switch ($dateType) {
			case 'Today': Configure::write('date', array(
					'from' => strtotime(date('Y-m-d')),
					'to' => time() + DAY
				));
				break;
			case 'Yesterday': Configure::write('date', array(
					'from' => strtotime(date('Y-m-d', time() - DAY)),
					'to' => strtotime(date('Y-m-d'))
				));
				break;
			case 'Last 30 days': Configure::write('date', array(
					'from' => strtotime(date('Y-m-d', time() - 30 * DAY)),
					'to' => time() + DAY
				));
				break;
			case 'Last 14 days': Configure::write('date', array(
					'from' => strtotime(date('Y-m-d', time() - 14 * DAY)),
					'to' => time() + DAY
				));
				break;

			default: // Last 7 days
				// $this->request->data('Filter.datesType', 'Last 7 days');
				Configure::write('date', array(
					'from' => strtotime(date('Y-m-d', time() - 7 * DAY)),
					'to' => time() + DAY
				));
		}*/
		/*
		fdebug(array(
			date('c', Configure::read('date.from')),
			date('c', Configure::read('date.to'))
		));
		*/
	}
}
