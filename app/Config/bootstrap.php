<?php
define('TEST_ENV', isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '192.168.1.22');

Cache::config('default', array('engine' => 'File'));

CakePlugin::loadAll();
CakePlugin::load('DebugKit');

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
// Configure::write('Exception.renderer', 'AppExceptionRenderer');
Configure::write('Config.language', 'eng');

/* -= Custom settings =- */
Configure::write('domain', array(
	'url' => $_SERVER['SERVER_NAME'],
	'title' => 'AdHelper.dev'
));

Configure::write('plugrush', array(
	'title' => 'PlugRush.com',
	'api' => 'https://www.plugrush.com/api',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'plugrush_api.log',
));

Configure::write('voluum', array(
	'title' => 'Voluum.com',
	'token_api' => 'https://security.voluum.com/login',
	'token_cache' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'voluum_token.cache',
	'api' => 'https://reports.voluum.com/report',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'voluum_api.log',
));

function fdebug($data, $logFile = 'tmp.log', $lAppend = true) {
	file_put_contents($logFile, mb_convert_encoding(print_r($data, true), 'cp1251', 'utf8'), ($lAppend) ? FILE_APPEND : null);
	return $data;
}

