<?
	$title = $this->ObjectType->getTitle('index', $objectType);
	$breadcrumbs = array(
		__('Users') => 'javascript:;',
		$title => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', compact('title'));
	echo $this->Flash->render();

	$columns = $this->PHTableGrid->getDefaultColumns($objectType);
	$columns[$objectType.'.key']['label'] = __('Licence key');

	$rowset = $this->PHTableGrid->getDefaultRowset($objectType);
	foreach($rowset as &$row) {
		$row[$objectType]['balance'] = $this->Price->format($row[$objectType]['balance'], 'rus');
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => $title))?>
			<div class="portlet-body dataTables_wrapper">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<a class="btn green" href="<?=$this->Html->url(array('action' => 'edit', 0))?>">
									<i class="fa fa-plus"></i> <?=$this->ObjectType->getTitle('create', $objectType)?>
								</a>
							</div>
						</div>
						<div class="col-md-6">

						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render($objectType, compact('columns', 'rowset'))?>
			</div>
		</div>
	</div>
</div>
