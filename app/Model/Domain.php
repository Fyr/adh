<?
/*
 * ������� ��� ����� �������� � ������ ��������� (������ �������� �� ����� ��������� �������� ���������� ID ���������� �������)
 */
App::uses('AppModel', 'Model');
class Domain extends AppModel {

    public function getOptions($ids) {
        $fields = array('id', 'domain');
        $conditions = array('id' => $ids);
        $order = 'domain';
        return $this->find('list', compact('conditions', 'fields', 'order'));
    }
}
