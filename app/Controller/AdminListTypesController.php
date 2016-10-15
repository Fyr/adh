<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminListTypesController extends AdminController {
    public $name = 'AdminListTypes';
    public $uses = array('ListType');

    public $paginate = array(
        'conditions' => array('campaign_id' => 0),
        'fields' => array('list_type', 'title', 'sorting'),
        'order' => array('sorting' => 'asc'),
        'limit' => 20
    );

    public function beforeRenderLayout() {
        parent::beforeRenderLayout();

        $aTypeOptions = $this->ListType->getTypeOptions();
        $this->set(compact('aTypeOptions'));
    }

    public function index() {
        $this->PCTableGrid->paginate('ListType');
    }

    public function edit($id = 0) {
        if ($this->request->is(array('put', 'post'))) {
            if ($id) {
                $this->request->data('ListType.id', $id);
            }
            if ($this->ListType->save($this->request->data)) {
                $this->Flash->success(__('Record has been successfully saved'));
                $id = $this->ListType->id;
                if ($this->request->data('apply')) {
                    $route = array('action' => 'index');
                } else {
                    $route = array('action' => 'edit', $id);
                }
                return $this->redirect($route);
            }
        } else {
            $this->request->data = $this->ListType->findById($id);
            if (!$id) {
                $this->request->data('ListType.sorting', '0');
            }
        }
    }
}
