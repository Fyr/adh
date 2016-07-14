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
<!-- form role="form" class="form-inline" action=""-->
<?
	echo $this->PHForm->create('Settings', array('class' => 'form-inline'));
?>
<h4>
	<img class="logo-service" src="/img/logo_plugrush.png" alt="<?=Configure::read('plugrush.title')?>"/>
	Plugrush.com
</h4>
<?
	echo $this->PHForm->input('plugrush_email', array('icon' => 'fa fa-envelope', 'label' => array('text' => 'Email/Login', 'class' => 'sr-only')));
	echo $this->PHForm->input('plugrush_psw', array('type' => 'password', 'icon' => 'fa fa-lock', 'label' => array('text' => 'Password', 'class' => 'sr-only')));
	echo $this->PHForm->input('plugrush_apikey', array('type' => 'password', 'icon' => 'fa fa-key', 'label' => array('text' => 'API Key', 'class' => 'sr-only')));
?>
<hr>
<h4>
	<img class="logo-service" src="/img/logo_voluum.png" alt="<?=Configure::read('voluum.title')?>"/>
	Voluum.com
</h4>
<?
	echo $this->PHForm->input('voluum_email', array('icon' => 'fa fa-envelope', 'label' => array('text' => 'Email/Login', 'class' => 'sr-only')));
	echo $this->PHForm->input('voluum_psw', array('type' => 'password', 'icon' => 'fa fa-lock', 'label' => array('text' => 'Password', 'class' => 'sr-only')));
	//echo $this->PHForm->input('plugrush_apikey', array('type' => 'password', 'icon' => 'fa fa-key', 'label' => array('text' => 'API Key', 'class' => 'sr-only')));
?>
<hr>
<h4>
	<img class="logo-service" src="/img/logo_popads.png" alt="<?=Configure::read('popads.title')?>"/>
	PopAds.com
</h4>
<?
	echo $this->PHForm->input('popads_email', array('icon' => 'fa fa-envelope', 'label' => array('text' => 'Email/Login', 'class' => 'sr-only')));
	echo $this->PHForm->input('popads_psw', array('type' => 'password', 'icon' => 'fa fa-lock', 'label' => array('text' => 'Password', 'class' => 'sr-only')));
	echo $this->PHForm->input('popads_apikey', array('type' => 'password', 'icon' => 'fa fa-key', 'label' => array('text' => 'API Key', 'class' => 'sr-only')));
//echo $this->PHForm->input('plugrush_apikey', array('type' => 'password', 'icon' => 'fa fa-key', 'label' => array('text' => 'API Key', 'class' => 'sr-only')));
?>
<hr>
<!--h4>Popads.net</h4>
	<div class="form-group">
		<label for="exampleInputEmail22" class="sr-only">Email address</label>
		<div class="input-icon">
			<i class="fa fa-envelope"></i>
			<input type="email" placeholder="Enter email" id="exampleInputEmail22" class="form-control"> </div>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword42" class="sr-only">Password</label>
		<div class="input-icon">
			<i class="fa fa-lock"></i>
			<i class="fa fa-lock"></i>
			<input type="password" placeholder="Password" id="exampleInputPassword42" class="form-control"> </div>
	</div-->
	<br/><br/>
<?
	echo $this->element('AdminUI/form_save');
	echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
