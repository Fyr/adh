<?php
App::uses('AppController', 'Controller');
App::uses('PCAuth', 'Core.Controller/Component');
App::uses('PCTableGrid', 'Table.Controller/Component');
App::uses('PHForm', 'Form.View/Helper');
App::uses('PHTime', 'Core.View/Helper');
App::uses('PHTableGrid', 'Table.View/Helper');
class AdminController extends AppController {
	public $name = 'Admin';
	public $layout = 'admin';
	// public $components = array();
	public $uses = array();

	public function _beforeInit() {
	    // auto-add included modules - did not included if child controller extends AdminController
	    $this->components = array_merge(array('Auth', 'Core.PCAuth', 'Flash', 'Paginator', 'Table.PCTableGrid'), $this->components);
	    $this->helpers = array_merge(array('Html', 'Form', 'Form.PHForm', 'Core.PHTime', 'Table.PHTableGrid'), $this->helpers);
	}
	
	public function beforeFilter() {
	}
	
	public function beforeRenderLayout() {
		$this->set('isAdmin', $this->isAdmin());
		$this->set('lang', 'eng');
	}
	
	public function isAdmin() {
		return AuthComponent::user('id') == 1;
	}

	public function isAuthorized($user) {
		$this->set('currUser', $user);
		return Hash::get($user, 'active');
	}

	public function index() {
		//$this->redirect(array('controller' => 'AdminProducts'));
	}
	
	protected function _getCurrMenu() {
		$curr_menu = str_ireplace('Admin', '', $this->request->controller); // By default curr.menu is the same as controller name
		return $curr_menu;
	}

	public function delete($id) {
		$this->autoRender = false;
		$model = $this->request->query('model');
		if ($model) {
			$this->loadModel($model);
			if (strpos($model, '.') !== false) {
				list($plugin, $model) = explode('.',$model);
			}
			$this->{$model}->delete($id);
		}
		if ($backURL = $this->request->query('backURL')) {
			$this->redirect($backURL);
			return;
		}
		$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
	}

}
