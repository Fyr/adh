<?
    $id = $this->request->data('CampaignGroup.id');
    $breadcrumbs = array(
        __('Lists') => 'javascript:;',
        __('List types') => array('action' => 'index'),
        __('Edit') => ''
    );
    echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
    echo $this->element('AdminUI/title', array('title' => __('Lists')));
    echo $this->Flash->render();
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">

<?
    echo $this->element('AdminUI/form_title', array('title' => $id ? __('Edit list type') : __('Create list type')));
    echo $this->PHForm->create('ListType');
    echo $this->PHForm->input('list_type', array('options' => $aTypeOptions));
    echo $this->PHForm->input('title');
    echo $this->PHForm->input('sorting', array('class' => 'form-control input-xsmall'));

    echo $this->element('AdminUI/form_actions');
    echo $this->PHForm->end();
?>
        </div>
    </div>
</div>
