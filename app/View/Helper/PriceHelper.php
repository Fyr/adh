<?php
App::uses('AppHelper', 'View/Helper');
class PriceHelper extends AppHelper {

	public function format($sum, $decimals = null) {
		$sign = ($sum < 0) ? '-' : '';
		$sum = abs($sum);
		if ($sum > 1 || $decimals) {
			$decimals = ($decimals) ? $decimals : Configure::read('Settings.decimals');
			$sum = number_format(
				$sum,
				$decimals,
				Configure::read('Settings.float_div'),
				Configure::read('Settings.int_div')
			);
		}
		$sum = $sign.Configure::read('Settings.price_prefix').$sum.Configure::read('Settings.price_postfix');
		return str_replace('$P', $this->symbolP(), $sum);
	}

	public function symbolP() {
		return '<span class="rubl">₽</span>';
	}

	public function jsFunction() {
		$script = '
function number_format( number, decimals, dec_point, thousands_sep ) {	// Format a number with grouped thousands
	var i, j, kw, kd, km;

	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	if( dec_point == undefined ){
		dec_point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

	return km + kw + kd;
}

	var Price = { format: function(sum, decimals){
		var sign = (sum < 0) ? "-" : "";
		sum = Math.abs(sum);
		if (sum > 1 || decimals) {
			decimals = (decimals) ? decimals : '.Configure::read('Settings.decimals').';
			sum = number_format(
				sum,
				decimals,
				"'.Configure::read('Settings.float_div').'",
				"'.Configure::read('Settings.int_div').'"
			);
		}
		return sign + "'.Configure::read('Settings.price_prefix').'" + sum + "'.Configure::read('Settings.price_postfix').'";
	}}
';
		return $script;
	}
}
