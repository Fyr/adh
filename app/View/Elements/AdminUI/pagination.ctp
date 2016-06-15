<div class="row">
	<div class="col-md-5 col-sm-5">
		<div class="dataTables_info">
<?
	echo $this->Paginator->counter(__('Showing {:start} to {:end} of {:count} records'));
?>
		</div>
	</div>
	<div class="col-md-7 col-sm-7">
<?
	// fdebug($this->Paginator->param('count').'!'.$this->Paginator->param('end').'!'.$this->Paginator->param('start'));
//	if (intval($this->Paginator->param('count')) > intval($this->Paginator->param('end'))) {
?>
		<div class="dataTables_paginate paging_bootstrap_full_number">
			<ul class="pagination">
<?
		echo $this->Paginator->first(
			'<i class="fa fa-angle-double-left"></i>',
			array('escape' => false, 'tag' => 'li'),
			'<a title="First" href="javascript:;"><i class="fa fa-angle-double-left"></i></a>',
			array('escape' => false, 'tag' => 'li', 'class' => 'prev disabled')
		);
		echo $this->Paginator->prev(
			'<i class="fa fa-angle-left"></i>',
			array('escape' => false, 'tag' => 'li'),
			'<a title="Prev" href="javascript:;"><i class="fa fa-angle-left"></i></a>',
			array('escape' => false, 'tag' => 'li', 'class' => 'prev disabled')
		);
		echo $this->Paginator->numbers(array(
			'separator' => '',
			'tag' => 'li',
			'currentTag' => 'a',
			'currentClass' => 'active'
		));
		echo $this->Paginator->next(
			'<i class="fa fa-angle-right"></i>',
			array('escape' => false, 'tag' => 'li'),
			'<a title="Next" href="javascript:;"><i class="fa fa-angle-right"></i></a>',
			array('escape' => false, 'tag' => 'li', 'class' => 'next disabled')
		);
		echo $this->Paginator->last(
			'<i class="fa fa-angle-double-right"></i>',
			array('escape' => false, 'tag' => 'li'),
			'<a title="Last" href="javascript:;"><i class="fa fa-angle-double-right"></i></a>',
			array('escape' => false, 'tag' => 'li', 'class' => 'next disabled')
		);
?>
			</ul>

		</div>
<?
//	}
?>

	</div>
</div>