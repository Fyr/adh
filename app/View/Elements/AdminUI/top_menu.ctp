<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner ">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="/">
				<!-- img src="/img/logo-white.png" alt="logo" class="logo-default" style="height: 30px; position: relative; top: -7px;" /-->
				<img class="logo-default" alt="logo" src="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/img/logo.png">
			</a>
			<div class="menu-toggler sidebar-toggler"> </div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">

				<!-- BEGIN USER LOGIN DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<span class="username username-hide-on-mobile"> <i class="icon-paper-plane"></i> <?=__('Campaigns')?> </span>
						<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="#<?//$this->Html->url(array('controller' => 'AdminUsers', 'action' => 'edit', 1))?>">
								<i class="icon-plus"></i> <?=__('Create campaign')?>
							</a>
						</li>
						<li>
							<a href="#<?//$this->Html->url(array('controller' => 'AdminAuth', 'action' => 'logout'))?>">
								<i class="icon-folder"></i> <?=__('Campaigns')?>
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown dropdown-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<!-- img alt="" class="img-circle" src="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/img/avatar3_small.jpg" /-->
						<span class="username username-hide-on-mobile"> <i class="icon-settings"></i> System </span>
						<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="<?=$this->Html->url(array('controller' => 'AdminUsers', 'action' => 'edit', 1))?>">
								<i class="icon-user"></i> <?=__('My profile')?>
							</a>
						</li>
						<li>
							<a href="<?=$this->Html->url(array('controller' => 'AdminAuth', 'action' => 'logout'))?>">
								<i class="icon-key"></i> <?=__('Log out')?>
							</a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="javascript:;" class="dropdown-toggle">
						<i class="icon-logout"></i>
					</a>
				</li>
				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>