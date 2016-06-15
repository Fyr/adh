<div class="form-group">
	<label class="col-md-3 control-label"><?//__('Status')?></label>
	<div class="col-md-9">
		<div class="checkbox-list">
<?
	if (!isset($checkboxes)) {
		$checkboxes = array('published', 'featured');
	}
	foreach($checkboxes as $modelField) {
		list($model, $field) = pluginSplit($modelField);
		if (!$model) {
			$model = $this->PHForm->defaultModel;
		}
		$options = array(
			'type' => 'checkbox',
			'label' => false,
			'div' => false
		);
		$label = (isset($labels) && isset($labels[$modelField])) ? $labels[$modelField] : ucfirst($field);
?>
			<label class="checkbox-inline">
				<?=$this->Form->input($model.'.'.$field, $options)?> <?=__($label)?>
			</label>
<?
	}
?>
		</div>
	</div>
</div>