<?
App::uses('AppModel', 'Model');
class ListType extends AppModel {

    const WHITE = 1;
    const BLACK = 2;

    public function getTypeOptions() {
        return array(
            self::WHITE => __('White lists'),
            self::BLACK => __('Black lists')
        );
    }

    public function getOptions($list_type = 0) {
        $fields = array('id', 'title');
        $conditions = ($list_type) ? compact('list_type') : array();
        $order = 'sorting';
        return $this->find('list', compact('conditions', 'fields', 'order'));
    }
}
