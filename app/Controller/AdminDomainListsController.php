<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
App::uses('ListType', 'Model');
class AdminDomainListsController extends AdminController {
    public $name = 'AdminDomainLists';
    public $uses = array('DomainList', 'ListType', 'DomainListDetails');

    public $paginate = array(
        // 'conditions' => array('campaign_id' => 0),
        'fields' => array('list_type', 'title', 'sorting'),
        'order' => array('sorting' => 'asc'),
        'limit' => 20
    );

    public function beforeRenderLayout() {
        parent::beforeRenderLayout();

        $aTypeOptions = $this->ListType->getOptions();
        $this->set(compact('aTypeOptions'));
    }

    public function index() {
        $aRowset = $this->PCTableGrid->paginate('DomainList');
        $list_ids = Hash::extract($aRowset, '{n}.DomainList.id');

        $conditions = array('list_id' => $list_ids);
        $order = array('list_id' => 'asc', 'domain' => 'asc');
        $aDomains = $this->DomainListDetails->find('all', compact('conditions', 'order'));
        $aDomains = Hash::combine($aDomains, '{n}.DomainListDetails.id', '{n}.DomainListDetails.domain', '{n}.DomainListDetails.list_id');
        $this->set(compact('aDomains'));
    }

    public function edit($id = 0) {
        if ($this->request->is(array('put', 'post'))) {
            if ($id) {
                $this->request->data('DomainList.id', $id);
            }
            try {
                $this->DomainList->trxBegin();
                if (!$this->DomainList->save($this->request->data)) {
                    throw new Exception(__('Save record error'));
                }

                $id = $this->DomainList->id;
                $this->_saveDomains($id, $this->_getDomainList($this->request->data('DomainList.domains')));

                $this->DomainList->trxCommit();
                $this->Flash->success(__('Record has been successfully saved'));

                if ($this->request->data('apply')) {
                    $route = array('action' => 'index');
                } else {
                    $route = array('action' => 'edit', $id);
                }
                return $this->redirect($route);
            } catch (Exception $e) {
                $this->DomainList->trxRollback();
                $this->Flash->error($e->getMessage());
            }
        } else {
            $this->request->data = $this->DomainList->findById($id);
            $this->request->data('DomainList.domains', implode("\n", $this->DomainList->getDomains($id)));
            if (!$id) {
                $this->request->data('ListType.sorting', '0');
            }
        }
    }

    private function _getDomainList($domains) {
        $domains = str_replace("\r\n", "\n", trim($domains));
        if (!$domains) {
            return array();
        }
        $domains = explode("\n", $domains);
        foreach($domains as &$domain) {
            $domain = strtolower(trim($domain));
        }
        return $domains;
    }

    private function _saveDomains($list_id, $aDomains) {
        $aExist = $this->DomainList->getDomains($list_id);
        $this->DomainList->removeDomains($list_id, array_diff($aExist, $aDomains));
        $this->DomainList->addDomains($list_id, array_diff($aDomains, $aExist));
    }
}
