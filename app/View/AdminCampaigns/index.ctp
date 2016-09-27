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

	$row_actions = false;// '../AdminCampaigns/_row_actions';
	$checkboxes = true;
	$columns = $this->PHTableGrid->getDefaultColumns('Campaign');
	unset($columns['Campaign.src_type']);
	unset($columns['Campaign.url']);
	unset($columns['Campaign.active']);
	unset($columns['Campaign.trk_data']);

	$columns['Campaign.src_id']['label'] = 'ID';
	$columns['Campaign.src_name']['label'] = 'Campaign name';
	$columns['Campaign.src_visits']['label'] = 'LP Visits';
	$columns['Campaign.trk_clicks']['label'] = 'LP Clicks';
	$columns['Campaign.ctr']['label'] = 'CTR';
	$columns['Campaign.cpv']['label'] = 'CPV';
	$columns['Campaign.roi']['label'] = 'ROI';
	$columns['Campaign.epv']['label'] = 'EPV';
	$columns['Campaign.trend'] = array(
		'key' => 'Campaign.trend',
		'label' => 'Trend',
		'format' => 'string'
	);

	$rowset = $this->PHTableGrid->getDefaultRowset('Campaign');
	foreach($rowset as &$_row) {
		$row = $_row['Campaign'];

		$src_type = Configure::read($row['src_type'].'.title');
		$icon = $this->Html->image('logo_'.$row['src_type'].'.png', array(
			'class' => 'logo-service',
			'alt' => $src_type,
			'title' => $src_type
		));
		$attrs = array('title' => $row['url']);
		$_row['Campaign']['src_name'] = $icon.' '.$this->Html->link($row['src_name'], 'javascript:;', $attrs);

		$icon = ($row['trk_data']) ? '' : $this->Html->div('pull-right', $this->Html->image('attention-icon.png', array(
			'class' => 'logo-service',
			'alt' => 'No tracker data!',
			'title' => 'No tracker data!'
		)));
		$class = ($row['active']) ? 'font-green-jungle' : 'font-red-thunderbird';
		$_row['Campaign']['status'] = $this->Html->tag('span', $_row['Campaign']['status'], compact('class')).$icon;

		foreach(array('bid', 'cost', 'revenue', 'cpv', 'epv') as $key) {
			$_row['Campaign'][$key] = $this->Price->format($row[$key], 2);
		}

		$_row['Campaign']['ctr'] = $_row['Campaign']['ctr'].'%';

		$class = ($row['profit'] >= 0) ? 'font-green-jungle' : 'font-red-thunderbird';
		$_row['Campaign']['profit'] = $this->Html->tag('span', $this->Price->format($row['profit'], 2), compact('class'));

		$class = ($row['roi'] >= 0) ? 'font-green-jungle' : 'font-red-thunderbird';
		$_row['Campaign']['roi'] = $this->Html->tag('span', $_row['Campaign']['roi'].'%', compact('class'));

		$_row['Campaign']['trend'] = $this->Html->tag('span', '', array('id' => 'trend-'.$row['id'], 'class' => 'trendChart'));
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
	/* .dataTable tbody td:nth-child(2), td:nth-child(3), td:nth-child(5), td:nth-child(6), td:nth-child(7) { white-space: nowrap; } */
	.dataTable tbody td:nth-child(16) { padding: 0; }
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
	echo $this->PHForm->end(); // 'columns', 'row_actions',
?>
							</div>

						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render('Campaign', compact('checkboxes', 'columns', 'rowset'))?>
			</div>
<?
	echo $this->PHForm->create('Campaign');
	$tabs = array(
		'Graphs' => $this->element('../AdminCampaigns/_graphs'),
		'Summary Report' => $this->element('../AdminCampaigns/_report'),
		'Site Targeting' => $this->element('../AdminCampaigns/_domains')
	);
	echo $this->Html->div('tabbable-bordered', $this->element('AdminUI/tabs', compact('tabs')));
	echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
<script>
function renderTrendCharts(e, data) {
	$(e).highcharts({
		chart: {
			//zoomType: 'xy',
			width: 200,
			height: 80
		},
		title: false,
		xAxis: [{
			categories: data.categories,
			crosshair: true,
			labels: {
				enabled: false
			}
		}],
		yAxis: [{ // Primary yAxis
			title: {
				text: false,
				style: {
					color: Highcharts.getOptions().colors[0]
				}
			},
			labels: {
				reserveSpace: false,
				x: -5,
				style: {
					color: Highcharts.getOptions().colors[0]
				}
			}
		}, { // Secondary yAxis
			title: {
				text: false,
				style: {
					color: Highcharts.getOptions().colors[1]
				}
			},
			labels: {
				format: '{value}%',
				x: 3,
				reserveSpace: false,
				style: {
					color: Highcharts.getOptions().colors[1]
				}
			},
			min: -100,
			max: 100,
			opposite: true
		}],
		tooltip: {
			shared: true
		},
		legend: {
			enabled: false,
		},
		series: [{
			name: 'Visits',
			type: 'column',
			yAxis: 0,
			data: data.visits,
		}, {
			name: 'ROI, %',
			type: 'spline',
			yAxis: 1,
			data: data.roi,
			tooltip: {
				valueSuffix: '%'
			}
		}],
		credits: {
			enabled: false
		}
	});
}

var data;
$(function() {
<?
	foreach($aStats as $campaign_id => $stats) {
		$data = array(
			'categories' => array(),
			'visits' => array(),
			'roi' => array()
		);
		foreach($stats as $row) {
			$data['categories'][] = date('d.m H:i', strtotime($row['created']));
			$data['visits'][] = intval($row['src_visits']);
			$data['roi'][] = intval($row['roi']);
		}
?>
	data = <?=json_encode($data)?>;
	renderTrendCharts('#trend-<?=$campaign_id?>', data);
<?
	}
?>

});
</script>


<script>
var timer = null;
$(function(){
	var startDate = Date.fromSqlDate('<?=$this->request->data('Filter.from')?>');
	var endDate = Date.fromSqlDate('<?=$this->request->data('Filter.to')?>');
	function showDateRange(start, end) {
		$('#FilterFrom').val(start.format('YYYY-MM-DD'));
		$('#FilterTo').val(end.format('YYYY-MM-DD'));
		$('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
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
		}, 800);
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