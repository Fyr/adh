<div class="container">
<?
	$title = __('Page not found');
	$class = 'light';
	echo $this->element('SiteUI/title', compact('title', 'class'));
?>
	<div class="article" style="margin-bottom: 100px">
		<p>Извините, по вашему запросу ничего не найдено либо запрашиваемая вами страница не существует.<br />
			Воспользуйтесь навигацией или поиском, чтобы найти необходимую вам информацию.<br />
			<br />
			<a href="/">На главную</a>
		</p>
	</div>
</div>