<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
	<?=$this->Html->charset()?>
	<title><?=Configure::read('domain.title')?> CMS</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=PT+Sans&subset=latin,latin-ext" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->

	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

	<!-- BEGIN THEME GLOBAL STYLES -->
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
	<!-- END THEME GLOBAL STYLES -->
	<!-- BEGIN THEME LAYOUT STYLES -->
	<link href="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
	<!-- END THEME LAYOUT STYLES -->

	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-summernote/summernote.css" rel="stylesheet" type="text/css" />

	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" type="text/css" />

	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />

	<!-- END PAGE LEVEL PLUGINS -->
<?
	echo $this->Html->meta('icon');
	echo $this->fetch('meta');
	echo $this->fetch('css');
?>
<style>
	.pagination .prev.disabled, .pagination .next.disabled { display: none; }
	.table.dataTable p {margin: 0 0 0.13em 0;}
	.rubl {font-family: 'PT Sans'}
	.form-text { margin-top: 8px;}
	.form-inline .form-group { margin-right: 10px; }
</style>
	<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white">
<!-- BEGIN HEADER -->
<?=$this->element('AdminUI/top_menu')?>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<?=$this->element('AdminUI/sidebar')?>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<!-- BEGIN CONTENT BODY -->
		<div class="page-content">
			<!-- BEGIN THEME PANEL -->
			<?//$this->element('AdminUI/theme_panel')?>
			<!-- END THEME PANEL -->

			<?=$this->fetch('content')?>

		</div>
		<!-- END CONTENT BODY -->
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		<?=__('Development')?>: <a href="http://kakadu.bz" target="_blank">Kakadu Dev</a>
		<!--
		2014 &copy; Metronic by keenthemes.
		<a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" target="_blank">Purchase Metronic!</a>
		-->
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!--[if lt IE 9]>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/respond.min.js"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js" type="text/javascript"></script-->
<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/scripts/app.min.js" type="text/javascript"></script-->

<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script-->

<script src="http://<?=Configure::read('domain.url')?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!--script src="http://<?=Configure::read('domain.url')?>/assets/pages/scripts/components-editors.min.js" type="text/javascript"></script-->
<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/scripts/datatable.js" type="text/javascript"></script-->
<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script-->
<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script-->
<!--script src="http://<?=Configure::read('domain.url')?>/assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script-->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js" type="text/javascript"></script>

<!-- script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script-->
<!--script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script-->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<!--script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script-->
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js" type="text/javascript"></script>

<script src="http://<?=Configure::read('domain.url')?>/js/vendor/jquery.cookie.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/js/lang.js" type="text/javascript"></script>
<?
	echo $this->Html->script(array('admin'));
	echo $this->fetch('script');
?>

</body>
</html>