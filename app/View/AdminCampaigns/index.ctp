<?
	$this->Html->script(array('/core/js/json_handler', 'vendor/highcharts', 'vendor/highcharts-grid-light-theme', 'vendor/tmpl.min', 'vendor/xtmpl'), array('inline' => false));

	$title = __('Campaigns list');
	$breadcrumbs = array(
		__('Campaigns') => 'javascript:;',
		$title => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	// echo $this->element('AdminUI/title', compact('title'));
	echo $this->Flash->render();

	$columns = array(
		/*
		'Campaign.check_all' => array(
			'key' => 'Campaign.checkbox',
			'label' => '<input type="checkbox">',
			'format' => 'string'
		),
		*/
		'Campaign.created' => array(
			'key' => 'Campaign.created',
			'label' => 'Created',
			'format' => 'string'
		),
		'Campaign.status' => array(
			'key' => 'Campaign.status',
			'label' => 'Status',
			'format' => 'string'
		),
		/*
		'Campaign.created' => array(
			'key' => 'Campaign.created',
			'label' => 'Created',
			'format' => 'datetime'
		),
		*/
		'Campaign.title' => array(
			'key' => 'Campaign.title',
			'label' => 'Campaign',
			'format' => 'text'
		),
		'Campaign.type' => array(
			'key' => 'Campaign.type',
			'label' => 'Type',
			'format' => 'string'
		),
		'Campaign.funds' => array(
			'key' => 'Campaign.funds',
			'label' => 'Traffic Funds',
			'format' => 'string'
		),
		'TrackerStats.funds' => array(
			'key' => 'TrackerStats.funds',
			'label' => 'Tracker Funds',
			'format' => 'string'
		),
		/*
		'Campaign.cost_profit' => array(
			'key' => 'Campaign.cost_profit',
			'label' => 'Cost./Profit',
			'format' => 'string'
		),
		*/
		'TrackerStats.cpv' => array(
			'key' => 'TrackerStats.cpv',
			'label' => 'CPV',
			'format' => 'string'
		),
		'TrackerStats.ctr' => array(
			'key' => 'TrackerStats.ctr',
			'label' => 'CTR',
			'format' => 'string'
		),
		'TrackerStats.roi' => array(
			'key' => 'TrackerStats.roi',
			'label' => 'ROI',
			'format' => 'string'
		),
	);
	$row_actions = false;// '../AdminCampaigns/_row_actions';
	$pagination = false;
	$checkboxes = true;

	foreach($rowset as &$row) {
		$row['Campaign']['created'] = implode('<br />', array(
			date('d.m.Y', strtotime($row['Campaign']['created'])),
			date('H:i', strtotime($row['Campaign']['created'])),
			'',
			date('d.m.Y', strtotime($row['Tracker']['created'])),
			date('H:i', strtotime($row['Tracker']['created'])),
		));

		$row['Campaign']['status'] = implode('<br />', array(
			$row['Campaign']['status'].' ('.round($row['Campaign']['traffic_received'] / $row['Campaign']['traffic_ordered'] * 100).'%)',
			$row['Campaign']['traffic_received'].'/'.$row['Campaign']['traffic_ordered']
		));

		$attrs = array('title' => $row['Campaign']['url']);
		$icon = $this->Html->image('logo_'.$row['Tracker']['src_type'].'.png', array(
			'class' => 'logo-service',
			'alt' => $row['Tracker']['src_title']
		));
		$row['Campaign']['title'] = implode('<br />', array(
			$icon.' '.$row['Tracker']['src_title'].' - '.'#'.$row['Campaign']['id'].' '.$row['Campaign']['title'],
			$this->Html->link(substr($row['Campaign']['url'], 0, 80).'...', $row['Campaign']['url'], $attrs),
			'',
			'Tracker: #'.$row['Tracker']['campaign_id'],
			$row['Tracker']['campaign_name'],
		));

		$row['Campaign']['type'] = implode('<br />', am(explode('_', $row['Campaign']['type']), array(
			'',
			$row['Tracker']['redirect_type'],
			$row['Tracker']['cost_model'],
		)));

		$row['Campaign']['funds'] = implode('<br/>', array(
			'Paid: <b>'.$this->Price->format($row['Campaign']['paid']).'</b>',
			'Bid: <b>'.$this->Price->format($row['Campaign']['bid']).'</b>',
			'Spent: <b>'.$this->Price->format($row['Campaign']['spent']).'</b>'
		));

		$class = ($row['TrackerStats']['roi'] < 0) ? 'font-red-thunderbird' : 'font-green-jungle';
		$row['TrackerStats']['funds'] = implode('<br/>', array(
			'Conv.: <b>'.$row['TrackerStats']['conversion'].'</b>',
			'Rev.: <b>'.$this->Price->format($row['TrackerStats']['revenue'], 2).'</b>',
			'Cost: <b>'.$this->Price->format($row['TrackerStats']['cost'], 2).'</b>',
			'Profit: '.$this->Html->tag('b', $this->Price->format($row['TrackerStats']['profit'], 2), compact('class'))
		));

		$row['TrackerStats']['cpv'] = $this->Price->format($row['TrackerStats']['cpv']);
		$row['TrackerStats']['ctr'] = ($row['TrackerStats']['ctr']) ? '~'.round($row['TrackerStats']['ctr'], 2).'%' : '0%';

		$row['TrackerStats']['roi'] = $this->Html->tag('span', $row['TrackerStats']['roi'].'%', compact('class'));

		// передаем через ID чекбокса все необходимые данные (ID трэкера, тип источника, ID источника)
		$row['Campaign']['id'] = implode(',', array(
			$row['Tracker']['campaign_id'],
			$row['Tracker']['src_type'],
			$row['Campaign']['id']
		));
	}
?>
<style>
	.checkboxes { text-align: center; }
	.dataTable tbody td:nth-child(2), td:nth-child(3), td:nth-child(5), td:nth-child(6), td:nth-child(7) { white-space: nowrap; }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => $title))?>
			<div class="portlet-body dataTables_wrapper">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<!--div class="btn-group">
								<a class="btn green" href="<?//$this->Html->url(array('action' => 'edit', 0))?>">
									<i class="fa fa-plus"></i> <?//$this->ObjectType->getTitle('create', $objectType)?>
								</a>
							</div-->
						</div>
						<div class="col-md-6 text-right">
							<div class="btn-group">
<?
/*
	echo $this->PHForm->create('Filter', array('class' => 'form-inline'));
	$options = array('Today', 'Yesterday', 'This week', 'Last 7 days', 'This month', 'Last 30 days');
	$options = array_combine($options, $options);
	if (!$this->request->data('Filter.datesType')) {
		$this->request->data('Filter.datesType', 'Last 7 days');
	}
	echo $this->PHForm->input('datesType', array('options' => $options, 'class' => 'form-control', 'autocomplete' => 'off'));
	echo $this->PHForm->end();
*/
?>
							</div>

						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render('Campaign', compact('columns', 'rowset', 'pagination', 'row_actions', 'checkboxes'))?>
			</div>
<?
	echo $this->PHForm->create('Campaign');
	$tabs = array(
		'Graphs' => $this->element('../AdminCampaigns/_graphs'),
		'Summary Report' => $this->element('../AdminCampaigns/_report'),
		'Site Targeting' => $this->element('../AdminCampaigns/_domains')
	);
	echo $this->element('AdminUI/tabs', compact('tabs'));
	echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
<script>
var timer = null;
$(function(){
	timer = null;
	updateCharts();

	$('.dataTable th.checkboxes input[type=checkbox]').change(function(e){
		e.stopPropagation();
		var checked = $(this).prop('checked');
		var $table = $(this).closest('table.dataTable');
		if ($(this).prop('checked')) {
			$('td.checkboxes .checker span', $table).addClass('checked');
		} else {
			$('td.checkboxes .checker span', $table).removeClass('checked');
		}
		$('td.checkboxes input', $table).prop('checked', checked);

		clearTimeout(timer);
		timer = setTimeout(function(){
			updateCharts();
		}, 700);
	});

	$('.dataTable td.checkboxes input[type=checkbox]').change(function(e){
		e.stopPropagation();
		clearTimeout(timer);
		timer = setTimeout(function(){
			updateCharts();
		}, 700);
	});
});

<?=$this->Price->jsFunction()?>
</script>