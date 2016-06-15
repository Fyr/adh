<div class="page-sidebar-wrapper">
	<!-- BEGIN SIDEBAR -->
	<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
	<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
		<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
		<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
		<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
			<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
			<li class="sidebar-toggler-wrapper hide">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler"> </div>
				<!-- END SIDEBAR TOGGLER BUTTON -->
			</li>
			<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
			<li class="sidebar-search-wrapper">
				<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
				<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
				<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
				<!--form class="sidebar-search  " action="page_general_search_3.html" method="POST">
					<a href="javascript:;" class="remove">
						<i class="icon-close"></i>
					</a>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<a href="javascript:;" class="btn submit">
								<i class="icon-magnifier"></i>
							</a>
						</span>
					</div>
				</form-->
				<!-- END RESPONSIVE QUICK SEARCH FORM -->
			</li>
			<li class="nav-item start ">
				<a href="<?=$this->Html->url(array('controller' => 'Admin', 'action' => 'index'))?>" class="nav-link">
					<i class="icon-home"></i>
					<span class="title"><?=__('Dashboard')?></span>
				</a>

			</li>
			<li class="heading">
				<h3 class="uppercase"><?=__('Admin area')?></h3>
			</li>
<?
/*
	$aMenu = array(
		array('label' => __('Static content'), 'icon' => 'icon-layers', 'url' => '', 'submenu' => array(
			array('label' => __('Pages'), 'url' => array('controller' => 'AdminPages', 'action' => 'index')),
			array('label' => __('News'), 'url' => array('controller' => 'AdminNews', 'action' => 'index')),
			array('label' => __('FAQ'), 'url' => array('controller' => 'AdminFaq', 'action' => 'index')),
			//array('label' => __('Blocks'), 'url' => array('controller' => 'AdminBlocks', 'action' => 'index')),
		)),
		array('label' => __('eCommerce'), 'icon' => 'icon-basket', 'url' => '', 'submenu' => array(
			array('label' => __('Categories'), 'url' => array('controller' => 'AdminCategories', 'action' => 'index')),
			array('label' => __('Products'), 'url' => array('controller' => 'AdminProducts', 'action' => 'index')),
		)),
		array('label' => __('Catalogs'), 'icon' => 'icon-playlist', 'url' => '', 'submenu' => array(
			array('label' => __('Songs'), 'url' => array('controller' => 'AdminSongs', 'action' => 'index')),
			array('label' => __('Song PDF-packs'), 'url' => array('controller' => 'AdminSongPacks', 'action' => 'index')),
			array('label' => __('Subscription plans'), 'url' => array('controller' => 'AdminSubscrPlans', 'action' => 'index')),
			array('label' => __('Personal order services'), 'url' => array('controller' => 'AdminServices', 'action' => 'index')),
			array('label' => __('Orders'), 'url' => array('controller' => 'AdminOrders', 'action' => 'index')),
		)),
		array('label' => __('Users'), 'icon' => 'icon-user', 'url' => '', 'submenu' => array(
			array('label' => __('User profiles'), 'url' => array('controller' => 'AdminUsers', 'action' => 'index')),
			array('label' => __('Admin profile'), 'url' => array('controller' => 'AdminUsers', 'action' => 'edit', 1)),
		)),
		array('label' => __('Settings'), 'icon' => 'icon-wrench', 'url' => '', 'submenu' => array(
			array('label' => __('System'), 'url' => array('controller' => 'AdminSettings', 'action' => 'index')),
			array('label' => __('Contacts'), 'url' => array('controller' => 'AdminSettings', 'action' => 'contacts')),
			array('label' => __('Prices'), 'url' => array('controller' => 'AdminSettings', 'action' => 'prices')),
			array('label' => __('Applications'), 'url' => array('controller' => 'AdminSettings', 'action' => 'apps')),
			array('label' => __('Catalogs'), 'url' => array('controller' => 'AdminSettings', 'action' => 'catalogs')),
			array('label' => __('Song packs'), 'url' => array('controller' => 'AdminSettings', 'action' => 'songpacks')),
			array('label' => __('Song pack discounts'), 'url' => array('controller' => 'AdminPackDiscounts', 'action' => 'index')),
			array('label' => __('Statuses'), 'url' => array('controller' => 'AdminSettings', 'action' => 'statuses')),
		)),
	);
*/

	$aMenu = array(
		array('label' => __('Campaigns'), 'icon' => 'icon-paper-plane', 'url' => '', 'submenu' => array(
			array('label' => __('Create campaign'), 'url' => array('controller' => 'AdminPages', 'action' => 'index')),
			array('label' => __('List'), 'url' => array('controller' => 'AdminNews', 'action' => 'index')),
		)),
		array('label' => __('Settings'), 'icon' => 'icon-wrench', 'url' => '', 'submenu' => array(
			array('label' => __('System'), 'url' => array('controller' => 'AdminSettings', 'action' => 'index')),
			array('label' => __('Accounts'), 'url' => array('controller' => 'AdminSettings', 'action' => 'accounts')),
		)),
	);
	$menuID = 0;
	$currMenu = 0;
	foreach($aMenu as $item) {
		$menuID++;
		$icon = (isset($item['icon']) && $item['icon']) ? '<i class="'.$item['icon'].'"></i>' : '';
		$label = '<span class="title">'.$item['label'].'</span>';
?>
			<li id="menu<?=$menuID?>" class="nav-item">
<?
		if (!isset($item['submenu'])) {
?>
				<a href="<?=$this->Html->url($item['url'])?>" class="nav-link">
					<?=$icon?>
					<?=$label?>
				</a>
<?
		} else {
?>
				<a href="javascript:;" class="nav-link nav-toggle">
					<?=$icon?>
					<?=$label?>
					<span class="arrow"></span>
				</a>
				<ul class="sub-menu">
<?
			foreach($item['submenu'] as $_item) {
				$menuID++;
				if ($this->request->controller == $_item['url']['controller'] && $this->request->action == $_item['url']['action']) {
					$currMenu = $menuID;
				}
				$icon = (isset($_item['icon']) && $_item['icon']) ? '<i class="'.$_item['icon'].'"></i>' : '';

				if ($_item['label'] == __('List')) {
?>
					<li class="nav-item open">
						<a class="nav-link nav-toggle" href="javascript:;">
							<span class="title">Campaign group 1</span>
							<span class="arrow open"></span>
						</a>
						<ul class="sub-menu" style="display: block;">
							<li class="nav-item">
								<a class="nav-link " href="ui_page_progress_style_1.html">Campaign 1</a>
							</li>
							<li class="nav-item ">
								<a class="nav-link " href="ui_page_progress_style_2.html">Campaign 2</a>
							</li>
						</ul>
					</li>
<?
				} else {
					$label = '<span class="title">'.$_item['label'].'</span>';
?>
					<li id="menu<?= $menuID ?>" class="nav-item">
						<a href="<?= $this->Html->url($_item['url']) ?>" class="nav-link">
							<span class="title"><?= $label ?></span>
						</a>
					</li>

<?
				}
			}
?>
				</ul>
<?
		}
?>
			</li>
<?
	}
?>
		</ul>
		<!-- END SIDEBAR MENU -->
		<!-- END SIDEBAR MENU -->
	</div>
	<!-- END SIDEBAR -->
</div>
<?
/*
	if ($this->request->controller == 'AdminPageBlocks') {
		$currMenu = 2;
	} elseif (in_array($this->request->controller,  array('AdminCategoryBlocks', 'AdminParamGroups', 'AdminParams'))) {
		$currMenu = 6;
	} elseif (in_array($this->request->controller, array('AdminProductBlocks', 'AdminProductPacks'))) {
		$currMenu = 7;
	} elseif ($this->request->controller == 'AdminSettings') {
		$submenu = array('index' => 18, 'contacts' => 19, 'prices' => 20, 'apps' => 21, 'catalogs' => 22, 'songpacks' => 23, 'statuses' => 25);
		$currMenu = $submenu[$this->request->action];
	} elseif ($this->request->controller == 'AdminUsers') {
		$currMenu = ($this->request->action == 'edit' && $this->request->pass[0] == 1) ? 16 : 15;
	} elseif ($this->request->controller == 'AdminPackDiscounts') {
		$currMenu = 24;
	}
*/
	if ($currMenu) {
?>
<script>
	$(function(){
		var $currMenu = $('#menu<?=$currMenu?>');
		$currMenu.addClass('active');
		$currMenu.addClass('open');
		$currMenu.parent().closest('li').addClass('active');
		$currMenu.parent().closest('li').addClass('open');
	});
</script>
<?
	}
?>
