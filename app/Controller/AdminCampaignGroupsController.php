<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminCampaignGroupsController extends AdminController {
    public $name = 'AdminCampaignGroups';
    public $uses = array('CampaignGroup', 'Campaign');

    public $paginate = array(
        'fields' => array('title', 'sorting'),
        'order' => array('created' => 'desc'),
        'limit' => 20
    );

    public function index() {
        $this->PCTableGrid->paginate('CampaignGroup');
    }

    public function edit($id = 0) {
        if ($this->request->is(array('put', 'post'))) {
            if ($id) {
                $this->request->data('CampaignGroup.id', $id);
            }
            if ($this->CampaignGroup->save($this->request->data)) {
                $this->Flash->success(__('Record has been successfully saved'));
                $id = $this->CampaignGroup->id;
                if ($this->request->data('apply')) {
                    $route = array('action' => 'index');
                } else {
                    $route = array('action' => 'edit', $id);
                }
                return $this->redirect($route);
            }
        } else {
            $this->request->data = $this->CampaignGroup->findById($id);
            if (!$id) {
                $this->request->data('CampaignGroup.sorting', '0');
            }
        }

        $aCampaigns = $this->Campaign->getSourceList();
        $this->set(compact('aCampaigns'));
        fdebug($aCampaigns);
    }
}
