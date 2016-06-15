<!DOCTYPE html>
<html lang="en">
<head>
	<?=$this->Html->charset()?>
	<title><?=Configure::read('domain.title')?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
<?

	echo $this->Html->css(array(
		'bootstrap.min',
		'owl-carousel/owl.carousel',
		'owl-carousel/owl.theme',
		'jquery.formstyler',
		'style.css',
		'extra'
	));

	echo $this->Html->meta('icon');
	echo $this->fetch('meta');
	echo $this->fetch('css');

	echo $this->Html->script(array(
		'vendor/jquery.1.11.0.min',
		'vendor/bootstrap.min',
		'vendor/owl.carousel.min',
		'vendor/jquery.cookie',
		'rego_custom',
		'lang',
		'vendor/jquery.formstyler.min',
		'/Core/js/json_handler'
	));
	echo $this->fetch('script');
?>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
<?=$this->element('SiteUI/navbar')?>
<div class="innerPage"></div>
<?=$this->fetch('content')?>
<div class="footer">
	<div class="container">
		<?=$this->element('SiteUI/footer')?>
	</div>
</div>
<?
	echo $this->element('SiteUI/login');
	echo $this->element('SiteUI/thanks');
?>
</body>
</html>
