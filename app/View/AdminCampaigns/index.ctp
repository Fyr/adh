<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<link href="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-orig.css" rel="stylesheet" type="text/css" />
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="http://<?=Configure::read('domain.url')?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker2.js" type="text/javascript"></script>

<?
	$this->Html->script(array(
		'/core/js/json_handler',
		'vendor/highcharts',
		'vendor/highcharts-grid-light-theme',
		'vendor/tmpl.min',
		'vendor/xtmpl',
		'vendor/xdate',
		'/Core/js/json_x',
		'/Table/js/format',
		'/Table/js/table_grid',
		'domains-grid'
	), array('inline' => false));
	// echo $this->element('Table.js_table_grid'); // нужен для table_grid

	$title = __('Campaigns list');
	$breadcrumbs = array(
		__('Campaigns') => 'javascript:;',
		$title => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->Flash->render();

	$columns = array(
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
		$created_date = (isset($row['Campaign']['created'])) ? date('d.m.Y', strtotime($row['Campaign']['created'])) : '';
		$created_time = (isset($row['Campaign']['created'])) ? date('H:i', strtotime($row['Campaign']['created'])) : '';
		$row['Campaign']['created'] = implode('<br />', array(
			$created_date,
			$created_time,
			'',
			date('d.m.Y', strtotime($row['Tracker']['created'])),
			date('H:i', strtotime($row['Tracker']['created'])),
		));

		$status = (isset($row['Campaign']['status'])) ? $row['Campaign']['status'] : '';
		$traffic_percent = (isset($row['Campaign']['traffic_percent'])) ? ' ('.$row['Campaign']['traffic_percent'].'%)' : '';
		$traffic_info = (isset($row['Campaign']['traffic_info'])) ? $row['Campaign']['traffic_info'] : '';
		$row['Campaign']['status'] = implode('<br />', array(
			$status.$traffic_percent,
			$traffic_info
		));

		$icon = $this->Html->image('logo_'.$row['Tracker']['src_type'].'.png', array(
			'class' => 'logo-service',
			'alt' => $row['Tracker']['src_title']
		));
		$title = '';
		if (isset($row['Campaign']['id']) && isset($row['Campaign']['title'])) {
			$title = $icon.' '.$row['Tracker']['src_title'].' - '.'#'.$row['Campaign']['id'].' '.$row['Campaign']['title'];
		}
		$url = '';
		if (isset($row['Campaign']['url'])) {
			$attrs = array('title' => $row['Campaign']['url']);
			$url = (isset($row['Campaign']['url'])) ? $this->Html->link(substr($row['Campaign']['url'], 0, 80) . '...', $row['Campaign']['url'], $attrs) : '';
		}
		$row['Campaign']['title'] = implode('<br />', array(
			$title,
			$url,
			'',
			'Tracker: #'.$row['Tracker']['campaign_id'],
			$row['Tracker']['campaign_name'],
		));

		$type = (isset($row['Campaign']['type'])) ? $row['Campaign']['type'] : '';
		$row['Campaign']['type'] = implode('<br />', array(
			$type,
			'',
			'',
			$row['Tracker']['redirect_type'],
			$row['Tracker']['cost_model'],
		));

		$items = array();
		if (isset($row['Campaign']['paid'])) {
			$items[] = 'Paid: <b>'.$this->Price->format($row['Campaign']['paid']).'</b>';
		}
		if (isset($row['Campaign']['bid'])) {
			$items[] = 'Bid: <b>'.$this->Price->format($row['Campaign']['bid']).'</b>';
		}
		if (isset($row['Campaign']['spent'])) {
			$items[] = 'Spent: <b>'.$this->Price->format($row['Campaign']['spent']).'</b>';
		}
		$row['Campaign']['funds'] = implode('<br/>', $items);

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
		if (isset($row['Campaign']['id'])) {
			$row['Campaign']['id'] = implode(',', array(
				$row['Tracker']['campaign_id'],
				$row['Tracker']['src_type'],
				$row['Campaign']['id']
			));
		} else {
			$row['Campaign']['id'] = '';
		}
	}


?>
<style>
	.daterangepicker_input .input-mini { width: 100% !important;}
	.daterangepicker td.start-date {
		border-radius: 4px 0 0 4px !important;
	}
	.daterangepicker td.end-date {
		border-radius: 0 4px 4px 0 !important;
	}
	.dataTable tbody td:nth-child(2), td:nth-child(3), td:nth-child(5), td:nth-child(6), td:nth-child(7) { white-space: nowrap; }
	.table > thead > tr > th.grid-x-header {border-bottom: 1px solid #e7ecf1; text-align: center;}
	.filter-list-header {font-weight: bold; margin: 10px 0 5px 0;}
	.filter-list-item {margin: 3px 0;}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', compact('title', 'actions'))?>

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
	echo $this->PHForm->create('Filter', array('class' => 'form-inline', 'id' => 'filterForm'));
?>
								<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 250px">
									<i class="fa fa-calendar"></i>&nbsp;
									<span></span> <b class="caret"></b>
								</div>
<?
	echo $this->PHForm->hidden('from');
	echo $this->PHForm->hidden('to');
/*
	$datesOptions = array_combine($datesOptions, $datesOptions);
	echo $this->PHForm->input('datesType', array('options' => $datesOptions, 'class' => 'form-control', 'autocomplete' => 'off', 'onchange' => "$('#filterForm').submit();"));
*/
	echo $this->PHForm->end();
?>
							</div>

						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render('Campaign', compact('columns', 'rowset', 'pagination', 'row_actions', 'checkboxes'))?>
			</div>
<?
	// echo $this->PHForm->create('Campaign');
	$tabs = array(
		'Graphs' => $this->element('../AdminCampaigns/_graphs'),
		'Summary Report' => $this->element('../AdminCampaigns/_report'),
		'Site Targeting' => $this->element('../AdminCampaigns/_domains')
	);
	echo $this->Html->div('tabbable-bordered', $this->element('AdminUI/tabs', compact('tabs')));
	// echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
<script>
var timer = null;
$(function(){
	var startDate = Date.fromSqlDate('<?=$this->request->data('Filter.from')?>');
	var endDate = Date.fromSqlDate('<?=$this->request->data('Filter.to')?>');
	function showDateRange(start, end) {
		$('#FilterFrom').val(start.format('YYYY-MM-DD'));
		$('#FilterTo').val(end.format('YYYY-MM-DD'));
		$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	}
	showDateRange(moment(startDate), moment(endDate));
	$('#reportrange').daterangepicker({
		startDate: startDate,
		endDate: endDate,
		maxDate: moment(),
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(7, 'days'), moment()],
			'Last 30 Days': [moment().subtract(30, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	}, function(start, end, label){
		showDateRange(start, end);
		$('#filterForm').submit();
	});

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