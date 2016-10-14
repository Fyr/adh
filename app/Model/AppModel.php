<?php
App::uses('Model', 'Model');
class AppModel extends Model {

    public function __construct($id = false, $table = null, $ds = null) {
        $this->_beforeInit();
        parent::__construct($id, $table, $ds);
        $this->_afterInit();
    }

    protected function _beforeInit() {
        // Add here behaviours, models etc that will be also loaded while extending child class
    }

    protected function _afterInit() {
        // after construct actions here
    }

    public function loadModel($modelClass = null, $id = null) {
        list($plugin, $modelClass) = pluginSplit($modelClass, true);

        $this->{$modelClass} = ClassRegistry::init(array(
            'class' => $plugin . $modelClass, 'alias' => $modelClass, 'id' => $id
        ));
        if (!$this->{$modelClass}) {
            throw new MissingModelException($modelClass);
        }

        return $this->{$modelClass};
    }

    public function getTableName() {
        return $this->getDataSource()->fullTableName($this);
    }

    public function setTableName($table) {
        $this->setSource($table);
    }

    public function trxBegin() {
        $this->getDataSource()->begin();
    }

    public function trxCommit() {
        $this->getDataSource()->commit();
    }

    public function trxRollback() {
        $this->getDataSource()->rollback();
    }

    public function getLang() {
        return Configure::read('Config.language');
    }

    protected function _writeLog($log, $actionType, $data = '') {
        if ($log) {
            if (is_array($data) || is_object($data)) {
                $data = serialize($data);
            }
            $string = date('d-m-Y H:i:s') . ' ' . $actionType . ' ' . $data;
            file_put_contents($log, $string . "\r\n", FILE_APPEND);
        }
    }

    protected function _adjustDateRange($from, $to = 0) {
        $from = (is_numeric($from)) ? $from : strtotime($from);
        $from = strtotime(date('Y-m-d 00:00:00', $from));

        if ($to) {
            $to = (is_numeric($to)) ? $to : strtotime($to);
            $to = strtotime(date('Y-m-d 23:59:59', $to));
        } else {
            $to = strtotime(date('Y-m-d 23:59:59'));
        }
        return compact('from', 'to');
    }

}
