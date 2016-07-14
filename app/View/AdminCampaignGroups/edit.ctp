<?
    $id = $this->request->data('CampaignGroup.id');
    $breadcrumbs = array(
        __('Campaigns') => 'javascript:;',
        __('Groups') => array('action' => 'index'),
        __('Edit') => ''
    );
    echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
    echo $this->element('AdminUI/title', array('title' => __('Campaigns')));
    echo $this->Flash->render();
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">

<?
    echo $this->element('AdminUI/form_title', array('title' => $id ? __('Edit group') : __('Create group')));
    echo $this->PHForm->create('CampaignGroup');

    $columns = array(
        'title' => array('key' => 'title', 'label' => 'Title', 'format' => 'string')
    );
    foreach($aCampaigns as &$row) {
        $attrs = array('title' => $row['url']);
        $icon = $this->Html->image('logo_'.$row['src_type'].'.png', array(
            'class' => 'logo-service',
            'alt' => $row['src_title']
        ));
        $row['title'] = implode('<br />', array(
            $icon.' '.$row['src_title'].' - '.'#'.$row['id'].' '.$row['title'],
            $this->Html->link(substr($row['url'], 0, 80).'...', $row['url'], $attrs)
        ));
    }

    $tabs = array(
        __('General') =>
            $this->PHForm->input('title')
            .$this->PHForm->input('sorting', array('class' => 'form-control input-xsmall')),
        __('Campaigns') => $this->PHTableGrid->render('', array(
            'rowset' => $aCampaigns,
            'columns' => $columns,
            'pagination' => false,
            'checkboxes' => true,
            'checked' => explode(',', $this->request->data('CampaignGroup.campaign_ids')),
            'row_actions' => false
        )).$this->PHForm->hidden('campaign_ids')
    );

    echo $this->element('AdminUI/tabs', compact('tabs'));
    echo $this->element('AdminUI/form_actions');
    echo $this->PHForm->end();
?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    /*
    var ids = [<?=$this->request->data('CampaignGroup.ids')?>];
    for(var i = 0; i < ids.length; i++) {

    }
    */
    $('[type="checkbox"]').change(function(){
        var ids = [];
        $('tbody [type="checkbox"]:checked').each(function(){
            ids.push($(this).val());
        });
        $('#CampaignGroupCampaignIds').val(ids.join(','));
    });
});
</script>