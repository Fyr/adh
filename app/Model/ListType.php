<?
App::uses('AppModel', 'Model');
class ListType extends AppModel {
    public $useTable = false;

    const WHITE = 1;
    const BLACK = 2;

    public function getOptions() {
        return array(
            self::WHITE => __('White lists'),
            self::BLACK => __('Black lists')
        );
    }

}
