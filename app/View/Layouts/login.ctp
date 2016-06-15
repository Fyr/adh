<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<?=$this->Html->charset();?>
	<title><?=Configure::read('domain.title')?> CMS - <?=__('Authorization')?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
	<?=$this->Html->css('/assets/global/plugins/bootstrap/css/bootstrap.min'); ?>
	<!--link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /-->
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN THEME GLOBAL STYLES -->
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
	<!-- END THEME GLOBAL STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="http://<?=Configure::read('domain.url')?>/assets/pages/css/login-3.min.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL STYLES -->
	<!-- BEGIN THEME LAYOUT STYLES -->
	<!-- END THEME LAYOUT STYLES -->
	<?=$this->Html->meta('icon');?>
</head>
<!-- END HEAD -->

<body class=" login">
<!-- BEGIN LOGO -->
<div class="logo">
	<!-- a href="index.html"><img src="/img/logo-white.png" alt="" style="height: 60px;" /></a-->
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<?=$this->fetch('content')?>
</div>
<!-- END LOGIN -->
<!--[if lt IE 9]>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/respond.min.js"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/pages/scripts/login.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>