<?
	echo $this->Html->link('<i class="fa fa-info-circle"></i> '.__('Stats'), array('action' => 'view', $row['Campaign']['id']), array(
			'class' => 'btn btn-outline dark btn-sm green',
			'escape' => false
		)).' ';
