<style>
	.item {margin-bottom: 10px;}
</style>
<?
	$breadcrumbs = array(
		__('System') => 'javascript:;',
		__('Events') => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', array('title' => __('System')));
	echo $this->Flash->render();

	$columns = $this->PHTableGrid->getDefaultColumns('Task');
	unset($columns['Task.total']);
	unset($columns['Task.xdata']);
	$columns['Task.user_id']['label'] = 'User';
	$columns['Task.user_id']['format'] = 'string';

	$rowset = $this->PHTableGrid->getDefaultRowset('Task');
	$row_actions = false;

	foreach($rowset as &$_row) {
		$row = $_row['Task'];

		$_row['Task']['exec_time'] = $row['exec_time'].' sec';

		$_row['Task']['progress'] = $_row['Task']['progress'].'/'.$_row['Task']['total'];

		$icon = '';
		if ($row['status'] == Task::ABORT || $row['status'] == Task::ABORTED) {
			$class = 'font-blue';
		} elseif ($row['status'] == Task::ERROR) {
			$class = 'font-red-thunderbird';
			$icon = $this->Html->div('pull-right', $this->Html->image('attention-icon.png', array(
				'class' => 'logo-service',
				'alt' => 'Error!',
				'title' => unserialize($row['xdata'])
			)));
		} else {
			$class = 'font-green-jungle';
		}
		$_row['Task']['status'] = $this->Html->tag('span', $_row['Task']['status'], compact('class')).$icon;

		$_row['Task']['user_id'] = ($row['user_id']) ? 'Admin' : 'System';
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => __('Events')))?>
			<div class="portlet-body dataTables_wrapper">
				<!-- div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<a class="btn green" href="<?=$this->Html->url(array('action' => 'clear', 0))?>">
									<i class="fa fa-plus"></i> <?=__('Clear events')?>
								</a>
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div -->
				<?=$this->PHTableGrid->render('Task', compact('columns', 'rowset', 'row_actions'))?>
			</div>
		</div>
	</div>
</div>
