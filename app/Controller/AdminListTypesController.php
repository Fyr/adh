<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
App::uses('ListType', 'Model');
class AdminListTypesController extends AdminController {
    public $name = 'AdminListTypes';
    public $uses = array('ListType', 'DomainList');

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
            try {
                $this->ListType->trxBegin();
                if (!$this->ListType->save($this->request->data)) {
                    throw new Exception(__('Save record error'));
                }

                $id = $this->ListType->id;
                $this->_saveDomains($id, $this->_getDomainsList($this->request->data('ListType.domains')));

                $this->ListType->trxCommit();
                $this->Flash->success(__('Record has been successfully saved'));

                if ($this->request->data('apply')) {
                    $route = array('action' => 'index');
                } else {
                    $route = array('action' => 'edit', $id);
                }
                return $this->redirect($route);
            } catch (Exception $e) {
                $this->ListType->trxRollback();
                $this->Flash->error($e->getMessage());
            }
        } else {
            $this->request->data = $this->ListType->findById($id);
            if (!$id) {
                $this->request->data('ListType.sorting', '0');
            }
        }
    }

    private function _getDomainsList($domains) {
        $domains = explode("\n", str_replace("\r\n", "\n", $domains));
        foreach($domains as &$domain) {
            $domain = trim($domain);
        }
        return $domains;
    }

    private function _saveDomains($list_id, $aDomains) {
        fdebug($aDomains);
        $aDelete = array();
        $aRowset = $this->DomainList->getOptions($list_id);
        foreach($aRowset as $id => $domain) {
            if (!in_array($domain, $aDomains)) {
                $aDelete[] = $domain;
            }
        }
        $this->DomainList->deleteDomains($aDelete);
        foreach($aDomains as $domain) {
            if (!in_array($domain, $aRowset)) {
                $this->DomainList->clear();
                $this->DomainList->save(compact('list_id', 'domain'));
            }
        }
    }
}
