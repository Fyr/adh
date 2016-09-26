<?php
App::uses('Category', 'Model');
App::uses('Product', 'Model');
class AppController extends Controller {
	public $components = array('DebugKit.Toolbar',
		'Auth' => array(
			'authorize'      => array('Controller'),
			'loginAction'    => array('plugin' => '', 'controller' => 'pages', 'action' => 'home', '?' => array('login' => 1)),
			'loginRedirect'  => array('plugin' => '', 'controller' => 'user', 'action' => 'index'),
			'ajaxLogin' => 'Core.ajax_auth_failed',
			'logoutRedirect' => '/',
			'authError'      => 'You must sign in to access that page'
		),
	);

	protected $aCategories, $aProducts, $currUser, $cart;

	public function __construct($request = null, $response = null) {
		$this->_beforeInit();
		parent::__construct($request, $response);
		$this->_afterInit();
	}

	protected function _beforeInit() {
		$this->helpers = array_merge(array('Html', 'Form', 'Paginator'), $this->helpers); // 'ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media', 'ObjectType'
	}

	protected function _afterInit() {
		// after construct actions here
		$this->loadModel('Settings');
		$this->Settings->initData();

		Configure::write('Settings.decimals', 2);
		Configure::write('Settings.float_div', '.');
		Configure::write('Settings.int_div', ',');
		Configure::write('Settings.price_prefix', '$');
		Configure::write('Settings.price_postfix', '');
	}

	public function loadModel($modelClass = null, $id = null) {
		if ($modelClass === null) {
			$modelClass = $this->modelClass;
		}

		$this->uses = ($this->uses) ? (array)$this->uses : array();
		if (!in_array($modelClass, $this->uses, true)) {
			$this->uses[] = $modelClass;
		}

		list($plugin, $modelClass) = pluginSplit($modelClass, true);

		$this->{$modelClass} = ClassRegistry::init(array(
			'class' => $plugin . $modelClass, 'alias' => $modelClass, 'id' => $id
		));
		if (!$this->{$modelClass}) {
			throw new MissingModelException($modelClass);
		}
		return $this->{$modelClass};
	}


	public function isAuthorized($user) {
		return Hash::get($user, 'active');
	}

	public function redirect404() {
		// return $this->redirect(array('controller' => 'pages', 'action' => 'notExists'), 404);
		throw new NotFoundException();
	}

	public function beforeFilter() {
		$this->beforeFilterLayout();
	}

	public function beforeFilterLayout() {
	}

	public function beforeRender() {
		$this->beforeRenderLayout();
	}

	protected function beforeRenderLayout() {
	}

	/**
	 * Runs shell in background (do not wait until shell is completed)
	 * @param $shell - shell name
	 */
	public function runBkg($method) {
		if (TEST_ENV) {
			fdebug("../Console/cake.bat {$method}\r\n", 'run.bat', false);
		} else {
			system("../Console/cake {$method} < /dev/null > task.log &");
		}
	}

}
