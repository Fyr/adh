var TableGrid = function() {
	var self = this;
	self.$ = [];

	self.columns = [];
	self.data = [];
	self.sortKey = null;
	self.sortDesc = false;
	self.checkboxes = false;
	self.checkboxes = false;
	self.primaryKey = 'id';
	self.rowActions = false;

	this.init = function(container, columns, data, settings) {
		self.$ = $(container);
		self.columns = columns;
		self.data = data;
		self.initColumns();
		self.initSettings(settings);
	};

	this.initColumns = function() {
		JSON.iterate(self.columns, function(col){
			if (!isset(col.sortable)) {
				col.sortable = true;
			}
		});
	};

	this.initSettings = function(settings) {
		for(var i in settings) {
			self[i] = settings[i];
		}
		/*
		self.checkboxes = isset(settings.checkboxes);
		self.primaryKey = isset(settings.primaryKey) ? settings.primaryKey : 'id';
		self.rowActions = isset(settings.rowActions) ? settings.rowActions : false;
		*/
	};

	this.setData = function(data) {
		self.data = data;
	};

	this.sortBy = function(colKey, lDesc) {
		self.sortKey = colKey;
		self.sortDesc = lDesc;
		JSON.sortBy(self.data, colKey, lDesc);
		self.render();
	};

	this.render = function() {
		var html = Format.tag('table', {class: 'table table-striped table-bordered table-hover table-header-fixed dataTable'},
			Format.tag('thead', null, this.renderHeader()) + Format.tag('tbody', null, this.renderBody())
		);
		self.$.html(html);
		self.initHandlers();
	};

	this.renderCheckbox = function(attrs) {
		attrs.type = 'checkbox';
		attrs.autocomplete = 'off';
		return Format.tag('div', {class: 'checker'},
			Format.tag('span', null,
				Format.tag('input', attrs)
			)
		)
	};

	this.renderHeader = function() {
		var html = '';
		if (self.checkboxes) {
			html+= Format.tag('th', {class: 'checkboxes'},
				self.renderCheckbox({id: 'check-all'})
			);
		}
		for(var i = 0; i < self.columns.length; i++) {
			var col = self.columns[i];
			var attrs = {id: col.key, class: 'grid-header'};
			if (col.sortable) {
				attrs.class+= ' grid-sortable sorting';
				if (self.sortKey == col.key) {
					attrs.class+= (self.sortDesc) ? ' sorting_desc' : ' sorting_asc';
				}
			}
			html+= Format.tag('th', attrs, col.label);
		}
		if (self.rowActions) {
			html+= Format.tag('th', null, 'Actions');
		}
		return html;
	};

	this.renderBody = function() {
		var html = '';
		JSON.iterate(self.data, function(row){
			var tr = '';
			var id = JSON.get(row, self.primaryKey);
			if (self.checkboxes) {
				tr+= Format.tag('td', {class: 'checkboxes'},
					self.renderCheckbox({name: 'data[checked][]', value: id})
				);
			}
			for(var j = 0; j < self.columns.length; j++) {
				var col = self.columns[j];
				var val = JSON.get(row, col.key);
				if (isset(col.render)) {
					val = col.render(val, row);
				} else {
					if (isset(val)) {
						var attrs = col.attrs;
						if (isset(col.format)) {
							if (col.format == 'int') {
								val = (!val) ? '0' : parseInt(val);
								attrs = {align: 'right'};
							} else if (col.format == 'percent') {
								val += '%';
								attrs = {align: 'right'};
							}
						}
					} else {
						val = '-';
						attrs = {align: 'center'};
					}
					val = Format.tag('td', attrs, val);
				}

				tr+= val;
			}
			if (self.rowActions) {
				tr+= Format.tag('td', {class: 'actions'}, self.rowActions(id, row));
			}
			html+= Format.tag('tr', null, tr);
		});
		return html;
	};

	this.initHandlers = function(){
		if (self.checkboxes) {
			$('thead th.checkboxes span', self.$).click(function(){
				$(this).toggleClass('checked');
				// var checked = $(this).hasClass('checked');
				$('tbody td.checkboxes span', self.$).removeClass('checked');
				if ($(this).hasClass('checked')) {
					$('tbody td.checkboxes span', self.$).addClass('checked');
				}
			});
			$('tbody td.checkboxes span', self.$).click(function(){
				$(this).toggleClass('checked');
			});
		}
		$('thead th.grid-sortable', self.$).click(function(){
			var lDesc = $(this).hasClass('sorting_asc');
			self.sortBy($(this).prop('id'), lDesc);
		});
	}
};
