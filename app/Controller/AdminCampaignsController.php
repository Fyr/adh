<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
class AdminCampaignsController extends AdminController {
    public $name = 'AdminCampaigns';
    public $uses = array('Campaign', 'VoluumApi', 'PlugRushApi', 'Settings', 'CampaignGroup', 'CampaignStats', 'PlugrushApi');
    public $helpers = array('Price');

    public $paginate = array(
        'fields' => array(
            'src_type', 'src_id', 'src_name', 'url', 'active', 'status', 'bid', 'src_visits', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv', 'trk_data'
        ),
        'order' => array('created' => 'desc'),
        'limit' => 10
    );

    public function index($group_id = null) {
        if (!$this->request->data('Filter.from')) {
            $this->request->data('Filter.from', date('Y-m-d', time() - 7 * DAY));
        }
        if (!$this->request->data('Filter.to')) {
            $this->request->data('Filter.to', date('Y-m-d'));
        }
        if ($group_id) {
            $group = $this->CampaignGroup->findById($group_id);
            if ($group) {
                $ids = explode(',', $group['CampaignGroup']['campaign_ids']);
                $this->paginate['conditions'] = array('id' => $ids);
            }
        }

        $aRowset = $this->PCTableGrid->paginate('Campaign');
        $ids = Hash::extract($aRowset, '{n}.Campaign.id');

        $this->set('aStats', $this->CampaignStats->getStats($ids, time() - DAY * 7));

        $options = array('Today', 'Yesterday', 'Last 7 days', 'Last 14 days', 'Last 30 days');
        $this->set('datesOptions', $options);

        $this->DomainStats = $this->loadModel('DomainStats');
        $domainStats = $this->DomainStats->getTotalStats(array(68, 71));
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
