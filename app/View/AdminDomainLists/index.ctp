<?
	$breadcrumbs = array(
		__('System') => 'javascript:;',
		__('Domain lists') => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', array('title' => __('System')));
	echo $this->Flash->render();

	$columns = $this->PHTableGrid->getDefaultColumns('DomainList');
	$columns['DomainList.list_type']['format'] = 'string';
	$columns['DomainList.domains'] = array(
		'key' => 'DomainList.domains',
		'label' => __('Domains'),
		'format' => 'string'
	);

	$rowset = $this->PHTableGrid->getDefaultRowset('DomainList');
	foreach($rowset as &$_row) {
		$row = $_row['DomainList'];
		$class = ($row['list_type'] == ListType::WHITE) ? 'font-green-jungle' : 'font-red-thunderbird';
		$_row['DomainList']['list_type'] = $this->Html->div($class, $aTypeOptions[$row['list_type']]);

		$class = ($row['list_type'] == ListType::WHITE) ? 'green' : 'red';
		$_row['DomainList']['domains'] = (!isset($aDomains[$row['id']])) ? '' : $this->element('more_items', array(
			'items' => $aDomains[$row['id']],
			'label' => 'domain(s)',
			'class' => $class
		));
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => __('Domain lists')))?>
			<div class="portlet-body dataTables_wrapper">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<a class="btn green" href="<?=$this->Html->url(array('action' => 'edit', 0))?>">
									<i class="fa fa-plus"></i> <?=__('Create list')?>
								</a>
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render('DomainList', compact('columns', 'rowset'))?>
			</div>
		</div>
	</div>
</div>
