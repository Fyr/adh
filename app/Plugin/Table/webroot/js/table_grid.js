var TableGrid = function() {
	var self = this;
	self.$ = [];

	self.columns = [];
	self.data = [];
	self.sortKey = null;
	self.sortDesc = false;

	this.init = function(container, columns, data) {
		self.$ = $(container);
		self.columns = columns;
		self.data = data;
		this.initColumns();
	};

	this.initColumns = function() {
		JSON.iterate(self.columns, function(col){
			if (!isset(col.sortable)) {
				col.sortable = true;
			}
		});
	};

	this.setData = function(data) {
		self.data = data;
	};

	this.sortBy = function(colKey, lDesc) {
		self.sortKey = colKey;
		self.sortDesc = lDesc;
		JSON.sortBy(self.data, colKey, lDesc);
		this.render();
	};
/*
	this.filterBy = function(colKey, filterType, options) {

	};
*/
	this.render = function() {
		var html = Format.tag('table', {class: 'table table-striped table-bordered table-hover table-header-fixed dataTable'},
			Format.tag('thead', null, this.renderHeader()) + Format.tag('tbody', null, this.renderBody())
		);
		self.$.html(html);
		this.initHandlers();
	};

	/*
	this.renderHeader = function() {
		return this._renderHeader();
	};
	*/
	this.renderHeader = function() {
		var html = '';
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
		return html;
	};

	this.renderBody = function() {
		var html = '';
		for(var i = 0; i < self.data.length; i++) {
			var row = self.data[i];
			var tr = '';
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
			html+= Format.tag('tr', null, tr);
		}
		return html;
	};

	this.initHandlers = function(){
		$('thead th.grid-sortable').click(function(){
			var lDesc = $(this).hasClass('sorting_asc');
			self.sortBy($(this).prop('id'), lDesc);
		});
	}
};
