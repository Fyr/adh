<?
App::uses('AppHelper', 'View/Helper');
App::uses('TimeHelper', 'View/Helper');
// class PHTimeHelper extends AppHelper {
class PHTimeHelper extends TimeHelper {
	function niceShort($dateString = null, $userOffset = null) {
		// по умолчанию - выводим полный формат, но без времени
		$date = strtotime($dateString);
		$day = date('j', $date);
		$month = ' '.__(date('M', $date), true);
		$year = ' '.date('Y', $date).'г.'; // (date('Y') == date('Y', $date)) 2014-09-29 20:54:38
		$time = '';
		if (date('m') == date('m', $date) && date('Y') == date('Y', $date)) {
			if (date('d') == date('d', $date)) {
				$day = __('Today', true);
				$month = '';
				$year = '';
				$time = ', ' . date('H:i', $date);
			} elseif ((date('d') - 1) == date('d', $date)) {
				$day = __('Yesterday', true);
				$month = '';
				$year = '';
				$time = ', ' . date('H:i', $date);
			}
		}
		return $day.$month.$year.$time;
	}
}