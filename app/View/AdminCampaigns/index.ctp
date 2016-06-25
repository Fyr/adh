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
			'label' => 'ID',
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
			'label' => 'Title',
			'format' => 'text'
		),
		'Campaign.type' => array(
			'key' => 'Campaign.type',
			'label' => 'Type',
			'format' => 'string'
		),
		'Campaign.settings' => array(
			'key' => 'Campaign.settings',
			'label' => 'Type',
			'format' => 'string'
		),
		'Campaign.funds' => array(
			'key' => 'Campaign.funds',
			'label' => 'Funds',
			'format' => 'string'
		),
		'Campaign.traffic' => array(
			'key' => 'Campaign.traffic',
			'label' => 'Traffic',
			'format' => 'string'
		),
	);
	$row_actions = '../AdminCampaigns/_row_actions';
	$pagination = false;
	$checkboxes = true;

	foreach($rowset as &$row) {
		// $row['Campaign']['checkbox'] = '<input type="checkbox">';
		$row['Campaign']['created'] = '#'.$row['Campaign']['id'].'<br />'.$this->PHTime->niceShort($row['Campaign']['created']);

		/*
		$attrs = array(
			'class' => 'popovers',
			'data-original-title' => 'Campaign URL:', 'data-content' => $row['Campaign']['url'],
			'data-placement' => 'bottom', 'data-trigger' => 'hover', 'data-container' => 'body')
		;
		*/
		$attrs = array('title' => $row['Campaign']['url']);
		$row['Campaign']['title'] = $row['Campaign']['title'].'<br />'.$this->Html->link(substr($row['Campaign']['url'], 0, 80).'...', $row['Campaign']['url'], $attrs);
		$row['Campaign']['type'] = str_replace('_', '<br/>', $row['Campaign']['type']);
		$row['Campaign']['settings'] = 'Autorenew: <b>'.$row['Campaign']['autorenew'].'</b><br/>Hour limit: <b>'.$row['Campaign']['max_hits_per_hour'].'</b>';

		$row['Campaign']['funds'] = 'Paid: <b>'.$this->Price->format($row['Campaign']['paid']).'</b><br/>Bid: <b>'.$this->Price->format($row['Campaign']['bid']).'</b><br/>Spent: <b>'.$this->Price->format($row['Campaign']['spent']).'</b>';
		$row['Campaign']['traffic'] = round($row['Campaign']['traffic_received'] / $row['Campaign']['traffic_ordered'] * 100).'%<br/>'.$row['Campaign']['traffic_received'].'/'.$row['Campaign']['traffic_ordered'].'';
	}
?>
<style>
	.checkboxes { text-align: center; }
	.dataTable tbody td:nth-child(2), td:nth-child(6), td:nth-child(7) { white-space: nowrap; }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => $title))?>
			<div class="portlet-body dataTables_wrapper">
				<!--div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<a class="btn green" href="<?//$this->Html->url(array('action' => 'edit', 0))?>">
									<i class="fa fa-plus"></i> <?//$this->ObjectType->getTitle('create', $objectType)?>
								</a>
							</div>
						</div>
						<div class="col-md-6">

						</div>
					</div>
				</div-->
				<?=$this->PHTableGrid->render('Campaign', compact('columns', 'rowset', 'pagination', 'row_actions', 'checkboxes'))?>
			</div>
<?
	echo $this->PHForm->create('Campaign');
	$tabs = array(
		'Graphs' => $this->element('../AdminCampaigns/_graphs'),
		'Summary Report' => '<div id="summary-report"></div>'
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
</script>