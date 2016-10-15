<?
    $attrs = array('title' => $campaign['url']);
    $src_type = Configure::read($campaign['src_type'].'.title');
    $icon = $this->Html->image('logo_'.$campaign['src_type'].'.png', array(
        'class' => 'logo-service',
        'alt' => $src_type
    ));
    $class = ($campaign['active']) ? 'font-green-jungle' : 'font-red-thunderbird';
    echo implode('<br />', array(
        $icon.' '.$src_type.' #'.$campaign['src_id'].' '.$campaign['src_name'].' ('.$this->Html->tag('span', $campaign['status'], compact('class')).')',
        $this->Html->link(substr($campaign['url'], 0, 80).'...', $campaign['url'], $attrs)
    ));