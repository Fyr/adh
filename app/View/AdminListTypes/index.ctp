<?
	$breadcrumbs = array(
		__('Lists') => 'javascript:;',
		__('List types') => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', array('title' => __('Lists')));
	echo $this->Flash->render();

	$columns = $this->PHTableGrid->getDefaultColumns('ListType');
	$columns['ListType.list_type']['format'] = 'string';
	$rowset = $this->PHTableGrid->getDefaultRowset('ListType');
	foreach($rowset as &$_row) {
		$row = $_row['ListType'];
		$class = ($row['list_type'] == ListType::WHITE) ? 'font-green-jungle' : 'font-red-thunderbird';
		$_row['ListType']['list_type'] = $this->Html->div($class, $aTypeOptions[$row['list_type']]);
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => __('List types')))?>
			<div class="portlet-body dataTables_wrapper">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<a class="btn green" href="<?=$this->Html->url(array('action' => 'edit', 0))?>">
									<i class="fa fa-plus"></i> <?=__('Create list type')?>
								</a>
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render('ListType', compact('columns', 'rowset'))?>
			</div>
		</div>
	</div>
</div>
