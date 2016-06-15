<?
	$title = __('Accounts');
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
	// echo $this->PHForm->create('Settings');
?>


<form role="form" class="form-inline" action="">
	<h4>Plugrush.com</h4>
	<div class="form-group">
		<label for="exampleInputEmail22" class="sr-only">Email</label>
		<div class="input-icon">
			<i class="fa fa-envelope"></i>
			<input type="email" placeholder="Email" class="form-control" name="data[Settings][plugrush_email]" value="<?=$this->request->data('Settings.plugrush_email')?>">
		</div>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword42" class="sr-only">Password</label>
		<div class="input-icon">
			<i class="fa fa-user"></i>
			<input type="password" placeholder="Password" class="form-control" name="data[Settings][plugrush_email]" value="<?=$this->request->data('Settings.plugrush_email')?>">
		</div>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword42" class="sr-only">API Key</label>
		<div class="input-icon">
			<i class="fa fa-key"></i>
			<input type="password" placeholder="API Key" id="exampleInputPassword43" class="form-control">
		</div>
	</div>
<hr>
<h4>Popads.net</h4>
	<div class="form-group">
		<label for="exampleInputEmail22" class="sr-only">Email address</label>
		<div class="input-icon">
			<i class="fa fa-envelope"></i>
			<input type="email" placeholder="Enter email" id="exampleInputEmail22" class="form-control"> </div>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword42" class="sr-only">Password</label>
		<div class="input-icon">
			<i class="fa fa-user"></i>
			<input type="password" placeholder="Password" id="exampleInputPassword42" class="form-control"> </div>
	</div>
	<hr>
</form>

			<?
/*
	echo $this->PHForm->input('song_price_'.$lang, array(
		'class' => 'form-control input-small',
		'label' => array('class' => 'col-md-3 control-label', 'text' => __('Song price'))
	));
	echo $this->PHForm->input('catalog_features_'.$lang, array(
		'label' => array('class' => 'col-md-3 control-label', 'text' => __('Catalog features'))
	));
	echo $this->PHForm->input('catalog_video_'.$lang, array(
		'label' => array('class' => 'col-md-3 control-label', 'text' => __('Video (HTML-code)'))
	));
*/
	echo $this->element('AdminUI/form_save');
	// echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
