<?
	$breadcrumbs = array(
		__('Campaigns') => 'javascript:;',
		__('Groups') => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', array('title' => __('Campaigns')));
	echo $this->Flash->render();

	$columns = $this->PHTableGrid->getDefaultColumns('CampaignGroup');
	$columns['CampaignGroup.campaign_ids']['label'] = 'Campaigns';
	$rowset = $this->PHTableGrid->getDefaultRowset('CampaignGroup');
	foreach($rowset as &$_row) {
		$ids = explode(',', $_row['CampaignGroup']['campaign_ids']);
		$_row['CampaignGroup']['campaign_ids'] = $this->element('campaign_list', compact('ids', 'aCampaigns'));
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<?=$this->element('AdminUI/form_title', array('title' => __('Groups')))?>
			<div class="portlet-body dataTables_wrapper">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<a class="btn green" href="<?=$this->Html->url(array('action' => 'edit', 0))?>">
									<i class="fa fa-plus"></i> <?=__('Create group')?>
								</a>
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div>
				<?=$this->PHTableGrid->render('CampaignGroup', compact('columns', 'rowset'))?>
			</div>
		</div>
	</div>
</div>
