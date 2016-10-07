<?
App::uses('AppModel', 'Model');
class CampaignGroup extends AppModel {

    public function getOptions() {
        return $this->find('list', array('order' => 'sorting'));
    }
}
