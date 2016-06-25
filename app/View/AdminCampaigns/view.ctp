<?

    $breadcrumbs = array(
        __('Campaigns') => 'javascript:;',
        __('Campaigns list') => array('action' => 'index'),
        'View Campaign' => ''
    );
    echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
    $title = __('Campaign #%s `%s`', $campaign['Campaign']['id'], $campaign['Campaign']['title']);
    echo $this->element('AdminUI/title', compact('title'));
    echo $this->Flash->render();
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
<?
    $aInfo = array(
        'Created' => date('d.m.Y H:i', strtotime($campaign['Campaign']['created'])),
        'Status' => $campaign['Campaign']['status'],
        'Type' => str_replace('_', '/', $campaign['Campaign']['type']),
        'Autorenew' => $campaign['Campaign']['autorenew'],
        'Hour limit' => $campaign['Campaign']['max_hits_per_hour'],
        'Paid' => $this->Price->format($campaign['Campaign']['paid']),
        'Spent' => $this->Price->format($campaign['Campaign']['spent']),
        'Bid' => $this->Price->format($campaign['Campaign']['bid']),
        'Traffic' => round($campaign['Campaign']['traffic_received'] / $campaign['Campaign']['traffic_ordered'] * 100).'% ('.$campaign['Campaign']['traffic_received'].'/'.$campaign['Campaign']['traffic_ordered'].')'
    );
?>
    <table class="dataTable">
        <tbody>
<?
    foreach($aInfo as $label => $val) {
?>
        <tr>
            <td width="10%"><?=$label?></td>
            <td><b><?=$val?></b></td>
        </tr>
<?
    }
?>

        </tbody>
    </table>

        </div>
    </div>
</div>
