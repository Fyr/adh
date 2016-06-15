<?php
App::uses('AdminController', 'Controller');
App::uses('Settings', 'Model');
class AdminSettingsController extends AdminController {
    public $name = 'AdminSettings';
    public $uses = array('Settings');

    public function beforeFilter() {
		parent::beforeFilter();

        if ($this->request->is(array('post', 'put'))) {
            $this->request->data('Settings.id', 1);
            $this->Settings->save($this->request->data);
            $this->Flash->success(__('Settings have been successfully saved'));
            return $this->redirect(array('action' => $this->request->action));
        }
        $this->request->data = $this->Settings->getData();
	}

    public function index() {
    }

    public function accounts() {
    }
    
}
