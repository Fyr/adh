<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('CampaignGroup', 'Model');
App::uses('CampaignStats', 'Model');
App::uses('PlugrushApi', 'Model');
App::uses('PopadsApi', 'Model');
class AdminCampaignsController extends AdminController {
    public $name = 'AdminCampaigns';
    public $uses = array('Campaign', 'PlugrushApi', 'Settings', 'CampaignGroup', 'CampaignStats');
    public $helpers = array('Price');

    public $paginate = array(
        'conditions' => array(),
        'fields' => array(
            'src_type', 'src_id', 'src_name', 'url', 'active', 'status', 'bid',
            'src_visits', 'trk_visits', 'src_clicks', 'trk_clicks',
            'conversion', 'revenue', 'cost', 'profit', 'cpv', 'ctr', 'roi', 'epv', 'trk_epv', 'trk_data'
        ),
        'order' => array('created' => 'desc'),
        'limit' => 10
    );

    public function index($group_id = null) {
        $from = $this->request->query('from');
        if (!$from) {
            $from = date('Y-m-d', time() - 7 * DAY);
        }
        $to = $this->request->query('to');
        if (!$to) {
            $to = date('Y-m-d');
        }
        $this->set(compact('from', 'to'));

        $group_id = ($group_id) ? $group_id : intval($this->request->query('group_id'));
        if ($group_id) {
            $group = $this->CampaignGroup->findById($group_id);
            if ($group) {
                $ids = explode(',', $group['CampaignGroup']['campaign_ids']);
                $this->paginate['conditions']['id'] = $ids;
            }
        }
        $type_id = $this->request->query('type_id');
        if ($type_id) {
            $this->paginate['conditions']['src_type'] = $type_id;
        }
        $aRowset = $this->PCTableGrid->paginate('Campaign');
        $ids = Hash::extract($aRowset, '{n}.Campaign.id');

        $this->set('aStats', $this->CampaignStats->getStats($ids, time() - DAY * 7));

        $options = array('Today', 'Yesterday', 'Last 7 days', 'Last 14 days', 'Last 30 days');
        $this->set('datesOptions', $options);

        $aGroupOptions = $this->CampaignGroup->getOptions();
        $aGroupOptions = Hash::merge(array(' - any group - '), $aGroupOptions);
        $aTypeOptions = array(
            ' - any type - ',
            PlugrushApi::TYPE => Configure::read('plugrush.title'),
            PopadsApi::TYPE => Configure::read('popads.title')
        );
        $this->set(compact('aGroupOptions', 'aTypeOptions'));
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
