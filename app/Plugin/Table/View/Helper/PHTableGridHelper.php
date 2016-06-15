<?php
App::uses('AppHelper', 'View/Helper');
class PHTableGridHelper extends AppHelper {
    public $helpers = array('Paginator', 'Html');
    private $paginate;
    
	public function getDefaultColumns($modelName) {
		$aCols = $this->viewVar('_paginate.'.$modelName.'._columns');
		if (!$aCols) {
			return array();
		}
		$aKeys = Hash::extract($aCols, '{n}.key');
		return array_combine($aKeys, $aCols);
	}

	public function getDefaultRowset($modelName) {
		return $this->viewVar('_paginate.'.$modelName.'._rowset');
	}

	public function render($modelName, $options = array()) {
		//$this->Html->css(array('/Table/css/grid', '/Icons/css/icons'), array('inline' => false));
		$this->Html->script(array('/Table/js/grid'), array('inline' => false));

		$_paginate = $this->viewVar('_paginate.'.$modelName);

		$options = array_merge(array(
			'model' => $modelName,
			'order' => $_paginate['order'],
			'columns' => $this->getDefaultColumns($modelName),
			'rowset' => $this->getDefaultRowset($modelName),
			'row_actions' => 'Table.row_actions',
			'pagination' => true
		), $options);

		if ($options['rowset']) {
			return $this->_View->element('Table.table', compact('options'));
		}
		return $this->_View->element('Table.no_records', compact('options'));
	}

}