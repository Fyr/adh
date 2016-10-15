<?
    $this->Html->script('more_items', array('inline' => false));
?>
<span class="more-items">
    <button type="button" class="btn btn-default btn-sm expand-items <?=(isset($class)) ? $class : ''?>">
        <?=count($items)?> <?=(isset($label)) ? $label : 'item(s)'?>
        <i class="fa fa-angle-down"></i>
    </button>
    <div style="white-space: nowrap; display: none">
        <?=implode((isset($implode)) ? $implode : '<br/>', $items)?>
    </div>
    <button type="button" class="btn btn-default btn-sm collapse-items <?=(isset($class)) ? $class : ''?>" style="display: none"> collapse
        <i class="fa fa-angle-up"></i>
    </button>
</span>