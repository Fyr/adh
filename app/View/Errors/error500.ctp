<div class="container">
<?
	$title = __('Page not found');
	$class = 'light';
	echo $this->element('SiteUI/title', compact('title', 'class'));
?>
	<div class="article" style="margin-bottom: 100px">
		<p>
			<b>Внимание!</b> По вашему запросу произошла ошибка сервера.<br />
			Наши специалисты уже занимаются ей, в ближайее время ошибка будет исправлена.<br />
			<br />
			<a href="/">На главную</a>
		</p>
	</div>
</div>