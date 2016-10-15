<?
    $this->Html->script('more_items', array('inline' => false));
?>
<span class="more-items">
    <button type="button" class="btn btn-sm expand-items <?=$class?>">
        <?=count($items)?> <?=(isset($label)) ? $label : 'item(s)'?>
        <i class="fa fa-angle-down"></i>
    </button>
    <div style="white-space: nowrap; display: none">
        <?=implode('<br/>', $items)?>
    </div>
    <button type="button" class="btn btn-sm collapse-items <?=$class?>" style="display: none"> collapse
        <i class="fa fa-angle-up"></i>
    </button>
</span>