<?
    $items = array();
    foreach($ids as $id) {
        $row = $aCampaigns[$id];
        $items[] = $this->Html->div('campaign-item', $this->element('campaign_item', array('campaign' => $row)));
    }
    echo $this->element('more_items', array('items' => $items, 'label' => __('campaign(s)'), 'implode' => ''));