<?php
define('TEST_ENV', isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '192.168.1.22');

Cache::config('default', array('engine' => 'File'));

CakePlugin::loadAll();
CakePlugin::load('DebugKit');

Cache::config('api', array(
	'engine' => 'File',
	'prefix' => 'api_',
	'path' => CACHE.'api'.DS,
	'serialize' => true,
	'duration' => '+1 day'
));

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

Configure::write('date', array(
	'from' => mktime(0, 0, 0, 6, 1, 2016),
	'to' => time() + DAY
));

Cache::config('tasks', array(
	'engine' => 'File',
	'duration' => '+999 days',
	'probability' => 100,
	'prefix' => 'tasks_',
	'serialize' => true,
	'mask' => 0664,
));

require_once('api.php');

function fdebug($data, $logFile = 'tmp.log', $lAppend = true) {
	file_put_contents($logFile, mb_convert_encoding(print_r($data, true), 'cp1251', 'utf8'), ($lAppend) ? FILE_APPEND : null);
	return $data;
}

