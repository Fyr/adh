<?
App::uses('AppModel', 'Model');
App::uses('CampaignDomain', 'Model');
class Campaign extends AppModel {

	const TYPE_PLUGRUSH = 'plugrush';
	const TYPE_POPADS = 'popads';

	public function getSourceList() {
		$fields = array('id', 'src_id', 'src_type', 'src_name', 'url', 'active', 'status');
		$aResult = $this->find('all', compact('fields'));
		return Hash::extract($aResult, '{n}.Campaign');
	}

	public function getList($ids = null) {
		$conditions = array('id' => $ids);
		$order = 'created DESC';
		$aResult = $this->find('all', compact('conditions', 'order'));
		return $aResult;
	}

	public function getDomainIds($id) {
		$this->CampaignDomain = $this->loadModel('CampaignDomain');
		$aData = $this->CampaignDomain->findAllByCampaignId($id);
		return Hash::extract($aData, '{n}.CampaignDomain.domain_id');
	}

}

