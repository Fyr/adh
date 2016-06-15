<?=$this->element('AdminUI/breadcrumbs', array('breadcrumbs' => array('' => '')))?>
<?=$this->element('AdminUI/title', array('title' => __('Dashboard')))?>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN SAMPLE FORM PORTLET-->
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => __('Start admin page')))?>
			<div class="portlet-body">
				<p>Sample start admin page content</p>
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>