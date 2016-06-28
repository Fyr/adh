<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
class AdminCampaignsController extends AdminController {
    public $name = 'AdminCampaigns';
    public $uses = array('Campaign', 'VoluumApi');
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
        $this->set('rowset', fdebug($aCampaigns));
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
