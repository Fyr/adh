<?php
App::uses('AppHelper', 'View/Helper');
class PriceHelper extends AppHelper {

	public function format($sum, $decimals = null) {
		Configure::write('Settings.decimals', 2);
		Configure::write('Settings.float_div', '.');
		Configure::write('Settings.int_div', ',');
		Configure::write('Settings.price_prefix', '$');
		Configure::write('Settings.price_postfix', '');

		if ($sum > 1 || $decimals) {
			$decimals = ($decimals) ? $decimals : Configure::read('Settings.decimals');
			$sum = number_format(
				$sum,
				$decimals,
				Configure::read('Settings.float_div'),
				Configure::read('Settings.int_div')
			);
		}
		$sum = Configure::read('Settings.price_prefix').$sum.Configure::read('Settings.price_postfix');
		return str_replace('$P', $this->symbolP(), $sum);
	}

	public function symbolP() {
		return '<span class="rubl">₽</span>';
	}
}
