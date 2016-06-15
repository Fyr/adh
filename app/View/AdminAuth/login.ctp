<!-- BEGIN LOGIN FORM -->
<form class="login-form" action="" method="post">
	<h3 class="form-title" style="text-align: center"><?=Configure::read('domain.title')?> CMS</h3>
	<?=$this->Flash->render();?>
	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<label class="control-label visible-ie8 visible-ie9"><?=__('Username')?></label>
		<div class="input-icon">
			<i class="fa fa-user"></i>
<?
	echo $this->Form->input('User.username', array(
		'autocomplete' => 'off',
		'class' => 'form-control placeholder-no-fix',
		'label' => false,
		'div' => false,
		'placeholder' => __('Username')
	));
?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9"><?=__('Password')?></label>
		<div class="input-icon">
			<i class="fa fa-lock"></i>
<?
	echo $this->Form->input('User.password', array(
		'autocomplete' => 'off',
		'class' => 'form-control placeholder-no-fix',
		'label' => false,
		'div' => false,
		'placeholder' => __('Password')
	));
?>
		</div>
	</div>
	<div class="form-actions" style="border-width: 0">
		<label class="checkbox"><input type="checkbox" name="remember" value="1" /> <?=__('Remember me')?> </label>
		<button type="submit" class="btn green pull-right"> <?=__('Login')?> </button>
	</div>
</form>
<!-- END LOGIN FORM -->