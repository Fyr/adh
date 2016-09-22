<?
$logsDir = ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS;
Configure::write('plugrush', array(
	'title' => 'PlugRush.com',
	'api' => 'https://www.plugrush.com/api',
	'log' => $logsDir.'plugrush_api.log',
	'cache' => false // 'api' - указываем cache config если нужно кеширование
));

Configure::write('voluum', array(
	'title' => 'Voluum.com',
	'token_api' => 'https://security.voluum.com/login',
	'token_cache' => CACHE.'api'.DS.'voluum_auth_token',
	'api' => 'https://reports.voluum.com/report',
	'log' => $logsDir.'voluum_api.log',
	'cache' => false
));

Configure::write('popads', array(
	'title' => 'PopAds.com',
	'api' => 'https://www.popads.net/api',
	'log' => $logsDir.'popads_api.log',
	'cache' => false
));
