var DomainListGrid = function(container, columns, data) {
	var self = this;

	extend(this, TableGrid);

	self.rules = [
		{key: 'eq', label: '='},
		{key: 'gt', label: '&gt;'},
		{key: 'gt_eq', label: '&ge;'},
		{key: 'lt', label: '&lt;'},
		{key: 'lt_eq', label: '&le;'},
		{key: 'range', label: '&le; x &le;'}
	];
	self.filters = [];
	self.oldData = [];

	/*
	this.setData = function(data) {
		self.data = data;
		self.oldData = data;
	};
	*/

	this.render = function() {
		$('#domains-filter').html(Tmpl('domains-filter').render(self));
		var html = Format.tag('table', {class: 'table table-striped table-bordered table-hover table-header-fixed dataTable'},
			Format.tag('thead', null, this.renderHeader()) + Format.tag('tbody', null, this.renderBody())
		);
		self.$.html(html);
		this.initHandlers();
	};

	this.renderHeader = function() {
		var html = Format.tag('tr', null,
			Format.tag('th', {colspan: 10, class: 'grid-x-header'}, 'Tracker') + Format.tag('th', {colspan: 3, class: 'grid-x-header'}, 'PlugRush.com')
		);
		return html + this.parent.renderHeader();
	};

	this.onSelectFilterRule = function() {
		$filter = $('#domainFilter');
		$('.filterOptions .filter-options', $filter).hide();
		var rule = ($('#rule', $filter).val() == 'range') ? 'range' : 'default';
		$('.filterOptions .filter-options-' + rule, $filter).show();
	};

	this.addFilter = function() {
		$filter = $('#domainFilter');
		var rule = $('#rule', $filter).val();
		var defaultValue = $('.filter-options-default input', $filter).val();
		var from = $('.filter-options-range input[name="from"]', $filter).val();
		var to = $('.filter-options-range input[name="to"]', $filter).val();
		options = (rule == 'range') ? {from: from, to: to} : defaultValue;
		self.filters.push({col: $('#cols', $filter).val(), rule: $('#rule', $filter).val(), options: options});
		self.renderFilterList();
		self.applyFilters();
	};

	this.renderFilterList = function() {
		$list = $('#domains-filter-list');
		$list.html(Tmpl('domains-filter-list').render(self));
	};

	this.removeFilter = function(i) {
		self.filters.splice(i, 1);
		self.renderFilterList();
		self.applyFilters();
	};

	this.isFiltered = function(data) {
		for(var i = 0; i < self.filters.length; i++) {
			var filter = self.filters[i];
			var value = JSON.get(data, filter.col);
			var lFlag = false;
			console.log(value, filter, data);
			if (filter.rule == 'gt') {
				lFlag = value > filter.options;
			} else if (filter.rule == 'gt_eq') {
				lFlag = value >= filter.options;
			} else if (filter.rule == 'lt') {
				lFlag = value < filter.options;
			} else if (filter.rule == 'lt_eq') {
				lFlag = value <= filter.options;
			} else if (filter.rule == 'range') {
				lFlag = filter.options.from <= value && value <= filter.options.to;
			} else {
				lFlag = (value == filter.options);
			}
			if (!lFlag) {
				return false;
			}
		}
		return true;
	};

	this.applyFilters = function() {
		if (!self.oldData.length) {
			self.oldData = self.data;
		}
		self.data = self.oldData;
		var newData = [];
		JSON.iterate(self.data, function(e){
			if (self.isFiltered(e)) {
				newData.push(e);
			}
		});
		self.setData(newData);
		self.render();
		self.setData(oldData);
	};
}