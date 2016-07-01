<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
class AdminCampaignsController extends AdminController {
    public $name = 'AdminCampaigns';
    public $uses = array('Campaign', 'VoluumApi', 'PlugRushApi');
    public $helpers = array('Price');
/*
    public $paginate = array(
        'conditions' => array('User.id <> ' => 1),
        'fields' => array('created', 'username', 'email', 'key', 'balance', 'active'),
        'order' => array('created' => 'desc'),
        'limit' => 20
    );
*/

    public function index() {
        $aCampaigns = $this->Campaign->getList();
        $this->set('rowset', $aCampaigns);

        // fdebug($this->loadModel('VoluumApi')->getDomainList('9b7c4cc9-2d9f-4d8f-a62d-2e82c2c76d16'));
        // fdebug($this->loadModel('PlugrushApi')->getDomainStats(7601546), 'tmp1.log');
    }

    public function view($id) {
        $aCampaigns = $this->Campaign->getList();
        if (!isset($aCampaigns[$id])) {
            $this->Flash->error(__('Incorrect campaign ID'));
            return $this->redirect(array('action' => 'index'));
        }

        $campaign = $aCampaigns[$id];
        $this->set(compact('campaign'));
    }

}
