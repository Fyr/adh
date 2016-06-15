<?
	$title = __('System');
	$breadcrumbs = array(
		__('Settings') => 'javascript:;',
		$title => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', array('title' => __('Settings')));
	echo $this->Flash->render();
?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">

<?
	echo $this->element('AdminUI/form_title', compact('title'));
	echo $this->PHForm->create('Settings');

	echo $this->PHForm->input('admin_email', array('label' => array('class' => 'col-md-3 control-label', 'text' => __('E-mail of admin'))));

	echo $this->element('AdminUI/form_save');
	echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
