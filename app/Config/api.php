<?
Configure::write('plugrush', array(
	'title' => 'PlugRush.com',
	'api' => 'https://www.plugrush.com/api',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'plugrush_api.log',
));

Configure::write('voluum', array(
	'title' => 'Voluum.com',
	'token_api' => 'https://security.voluum.com/login',
	'token_cache' => CACHE.'api'.DS.'voluum_auth_token',
	'api' => 'https://reports.voluum.com/report',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'voluum_api.log',
));

Configure::write('popads', array(
	'title' => 'PopAds.com',
	'api' => 'https://www.popads.net/api',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'popads_api.log',
));
