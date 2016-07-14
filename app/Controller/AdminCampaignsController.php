<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
class AdminCampaignsController extends AdminController {
    public $name = 'AdminCampaigns';
    public $uses = array('Campaign', 'VoluumApi', 'PlugRushApi', 'Settings');
    public $helpers = array('Price');
/*
    public $paginate = array(
        'conditions' => array('User.id <> ' => 1),
        'fields' => array('created', 'username', 'email', 'key', 'balance', 'active'),
        'order' => array('created' => 'desc'),
        'limit' => 20
    );
*/

    public function index($ids = null) {
        if (!$this->request->data('Filter.from')) {
            $this->request->data('Filter.from', date('Y-m-d', time() - 7 * DAY));
        }
        if (!$this->request->data('Filter.to')) {
            $this->request->data('Filter.to', date('Y-m-d'));
        }
        $this->Settings->adjustDateRange($this->request->data('Filter.from'), $this->request->data('Filter.to'));
        if ($ids) {
            $ids = explode(',', $ids);
        }
        $aCampaigns = $this->Campaign->getList($ids);
        $this->set('rowset', $aCampaigns);

        $options = array('Today', 'Yesterday', 'Last 7 days', 'Last 14 days', 'Last 30 days');
        $this->set('datesOptions', $options);
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
