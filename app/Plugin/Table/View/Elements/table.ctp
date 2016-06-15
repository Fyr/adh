<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
	<thead>
	<tr>
<?
	if (isset($options['checkboxes'])) {
?>
		<th class="checkboxes">
			<input type="checkbox" />
		</th>
<?
	}
	if ($options['order']) {
		list($order) = array_keys($options['order']);
		list($dir) = array_values($options['order']);
	} else {
		$order = '';
		$dir = '';
	}
	foreach($options['columns'] as $field) {
		$class = 'sorting';
		if ($field['key'] == $order) {
			$class = 'sorting_'.$dir;
		}
?>
		<th class="<?=$class?>">
			<?=$this->Paginator->sort($field['key'], $field['label'])?>
		</th>
<?
	}
	if ($options['row_actions']) {
?>
		<th><?=__('Actions')?></th>
<?
	}
?>
	</tr>
	</thead>
	<tbody>
<?
	foreach($options['rowset'] as $row) {
		$id = Hash::get($row, $options['model'].'.id');
?>
		<tr>
<?
		if (isset($options['checkboxes'])) {
?>
			<td class="checkboxes">
				<input type="checkbox" name="data[checked][]" value="<?=$id?>" />
			</td>
<?
		}
		foreach($options['columns'] as $field) {
			$field['value'] = Hash::get($row, $field['key']);
			if ($field['format'] == 'date' || $field['format'] == 'datetime') {
				echo $this->element('Table.date', $field);
			} elseif ($field['format'] == 'boolean') {
				echo $this->element('Table.boolean', $field);
			} elseif ($field['format'] == 'integer' || $field['format'] == 'float') {
				echo $this->element('Table.number', $field);
			} else {
				echo $this->element('Table.string', $field);
			}
		}
		if ($options['row_actions']) {
			echo '<td nowrap="nowrap">'.$this->element($options['row_actions'], compact('id', 'row')).'</td>';
		}
?>
		</tr>

<?
	}
?>

	</tbody>
</table>
<?
	if ($options['pagination']) {
		echo $this->element('AdminUI/pagination');
	}
?>