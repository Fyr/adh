<?
    $id = $this->request->data('DomainList.id');
    $breadcrumbs = array(
        __('System') => 'javascript:;',
        __('Domain lists') => array('action' => 'index'),
        __('Edit') => ''
    );
    echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
    echo $this->element('AdminUI/title', array('title' => __('System')));
    echo $this->Flash->render();
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">

<?
    echo $this->element('AdminUI/form_title', array('title' => $id ? __('Edit domain list') : __('Create domain list')));
    echo $this->PHForm->create('DomainList');
    $tabs = array(
        __('General') =>
            $this->PHForm->input('list_type', array('options' => $aTypeOptions))
            .$this->PHForm->input('title')
            .$this->PHForm->input('sorting', array('class' => 'form-control input-xsmall')),
        __('Domains') =>
            $this->PHForm->input('domains', array('type' => 'textarea'))
    );

    echo $this->element('AdminUI/tabs', compact('tabs'));
    echo $this->element('AdminUI/form_actions');
    echo $this->PHForm->end();
?>
        </div>
    </div>
</div>
