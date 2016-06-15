<ul class="nav nav-tabs">
<?
	$i = 0;
	foreach($tabs as $title => $content) {
		$i++;
		$class = ($i == 1) ? 'active' : '';
?>
	<li class="<?=$class?>">
		<a href="#tab_<?=$i?>" data-toggle="tab"> <?=$title?> </a>
	</li>
<?
	}
?>
</ul>
<div class="tab-content">
<?
	$i = 0;
	foreach($tabs as $title => $content) {
		$i++;
		$class = ($i == 1) ? 'tab-pane fade active in' : 'tab-pane fade';
?>
		<div class="<?=$class?>" id="tab_<?=$i?>">
			<?=$content?>
		</div>

<?
	}
?>
</div>
