<?
/*
	echo $this->Html->link('<i class="fa fa-edit"></i>', array('action' => 'view', $row['Campaign']['id']), array(
		'class' => 'text-success', // btn btn-outline dark
		'escape' => false
	)).' ';
	echo $this->Html->link('<i class="fa fa-remove"></i>', array('action' => 'view', $row['Campaign']['id']), array(
		'class' => 'text-danger', // btn btn-outline dark
		'escape' => false
	)).' ';
	echo $this->Html->link('<i class="fa fa-info-circle"></i>', array('action' => 'view', $row['Campaign']['id']), array(
		'class' => 'text-info', // btn btn-outline dark
		'escape' => false
	)).' ';
*/

	echo $this->Html->link('<i class="fa fa-edit"></i>', array('action' => 'view', $row['Campaign']['id']), array(
		'class' => 'btn btn-icon-only action-icon blue', // btn btn-outline dark
		'escape' => false
	));
	echo $this->Html->link('<i class="fa fa-remove"></i>', array('action' => 'view', $row['Campaign']['id']), array(
		'class' => 'btn btn-icon-only action-icon red', // btn btn-outline dark
		'escape' => false
	));
	echo $this->Html->link('<i class="fa fa-info-circle"></i>', array('action' => 'view', $row['Campaign']['id']), array(
		'class' => 'btn btn-icon-only action-icon green', // btn btn-outline dark
		'escape' => false
	));