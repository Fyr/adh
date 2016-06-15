<!-- BEGIN PAGE BAR -->
<div class="page-bar">
	<ul class="page-breadcrumb">
<?
	foreach($breadcrumbs as $title => $url) {
		if ($url) {
?>
			<li>
				<?=$this->Html->link($title, $url)?>
				<i class="fa fa-circle"></i>
			</li>
<?
		} else {
?>
			<li>
				<span><?=$title?></span>
			</li>
<?
		}
	}
?>
	</ul>
	<?//$this->element('AdminUI/page_toolbar') - not need yet ?>
</div>
<!-- END PAGE BAR -->