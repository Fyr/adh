<?php
App::uses('AppController', 'Controller');
class AdminAuthController extends AppController {
	public $name = 'AdminAuth';
	public $components = array('Core.PCAuth', 'Flash');
	public $layout = 'login';

	public function beforeFilter() {
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->loginRedirect);
			}
			$this->Flash->error('Invalid username or password');
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

}
