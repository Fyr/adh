<?
App::uses('AppModel', 'Model');
App::uses('CampaignDomain', 'Model');
class Campaign extends AppModel {

	const TYPE_PLUGRUSH = 'plugrush';
	const TYPE_POPADS = 'popads';

	public function getList($ids = null) {
        $fields = array('id', 'src_id', 'src_type', 'src_name', 'url', 'active', 'status');
		$conditions = ($ids) ? array('id' => $ids) : array();
		$order = array('src_type' => 'asc', 'src_name' => 'asc');
        return $this->find('all', compact('fields', 'conditions', 'order'));
	}

	public function getDomainIds($id) {
		$this->CampaignDomain = $this->loadModel('CampaignDomain');
		$aData = $this->CampaignDomain->findAllByCampaignId($id);
		return Hash::extract($aData, '{n}.CampaignDomain.domain_id');
	}

}

