<?
App::uses('AppModel', 'Model');
App::uses('DomainListDetails', 'Model');
class DomainList extends AppModel {

    public function getOptions($list_type = 0) {
        $fields = array('id', 'title');
        $conditions = ($list_type) ? compact('list_type') : array();
        $order = 'sorting';
        return $this->find('list', compact('conditions', 'fields', 'order'));
    }

    public function getDomains($list_id) {
        $fields = array('id', 'domain');
        $conditions = compact('list_id');
        $order = 'domain';
        return $this->loadModel('DomainListDetails')->find('list', compact('conditions', 'fields', 'order'));
    }

    public function addDomains($list_id, $domains) {
        $this->DomainListDetails = $this->loadModel('DomainListDetails');
        foreach($domains as $domain) {
            $this->DomainListDetails->clear();
            $this->DomainListDetails->save(compact('list_id', 'domain'));
        }
    }

    /*
     * ”далить домены из списка (domain - один или список доменов)
     */
    public function removeDomains($list_id, $domain) {
        $this->DomainListDetails = $this->loadModel('DomainListDetails');
        if ($list_id && $domain) {
            $this->DomainListDetails->deleteAll(compact('list_id', 'domain'));
        }
    }
}
