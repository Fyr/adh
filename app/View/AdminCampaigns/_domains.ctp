<div class="domains">
    <div id="domains-filter"></div>
    <div id="domains-filter-list"></div>
    <div id="domains-report"></div>
</div>
<script type="text/javascript">


var domainsGrid, columns, domains;
$(function(){
    function formatDomainName(domain, row) {
        if (!row.is_trk_data) {
            domain+= '<div class="pull-right"><img title="No tracker data!" alt="No tracker data!" class="logo-service" src="/img/attention-icon.png"></div>';
        }
        return Format.tag('td', null, domain);
    }

    function formatPrice(val) {
        if (isset(val)) {
            return Format.tag('td', {align: 'right'}, Price.format(val));
        }
        return Format.tag('td', {align: 'center'}, '-');
    };

    function formatColorPrice(val) {
        if (isset(val)) {
            attrs = {align: "right", class: (val < 0) ? 'font-red-thunderbird' : 'font-green-jungle'};
            return Format.tag('td', attrs, Price.format(val));
        }
        return Format.tag('td', {align: 'center'}, '-');
    };

    function formatColorPercent(val) {
        if (isset(val)) {
            attrs = {align: "right", class: (val < 0) ? 'font-red-thunderbird' : 'font-green-jungle'};
            return Format.tag('td', attrs, val + '%');
        }
        return Format.tag('td', {align: 'center'}, '-');
    };

    columns = [
        {key: 'domain', label: 'Domain', render: formatDomainName},
        {key: 'src_visits', label: 'Visits', format: 'int'},
        {key: 'trk_clicks', label: 'Clicks', format: 'int'},
        {key: 'conversion', label: 'Conv.', format: 'int'},
        {key: 'revenue', label: 'Rev.', render: formatPrice},
        {key: 'cost', label: 'Cost', render: formatPrice},
        {key: 'profit', label: 'Profit', render: formatColorPrice},
        {key: 'cpv', label: 'CPV', render: formatPrice},
        {key: 'ctr', label: 'CTR', format: 'percent'},
        {key: 'roi', label: 'ROI', render: formatColorPercent},
        {key: 'epv', label: 'EPV', render: formatPrice}
    ];

    // $('#domains-filter').html(tmpl('tmpl-domains-add-filter'));

    // domainsGrid = new DomainListGrid();
    domainsGrid = new TableGrid();
    domainsGrid.init('#domains-report', columns);
    var parent = {};
/*
    var parent_renderHeader = domainsGrid.renderHeader;
    domainsGrid.renderHeader = function() {
        var html = Format.tag('tr', null,
            Format.tag('th', {colspan: 10, class: 'grid-x-header'}, 'Tracker') + Format.tag('th', {colspan: 3, class: 'grid-x-header'}, 'PlugRush.com')
        );
        return html + parent_renderHeader();
    };
*/
    /*
    var parent_render = domainsGrid.render;
    domainsGrid.render = function() {
        parent_render();
        JSON.iterate(columns, function(col){
            if (col.key != 'domain') {
                $('#domainfilter select#cols').append(Format.tag('option', {value: col.key}, col.label));
            }
        });
    };
    */
});
</script>

<script type="text/x-tmpl" id="tmpl-domains-filter">
    <form id="domainFilter" class="form-inline">
        <div class="form-group">
            <select id="cols" class="form-control input-small">
{%
    JSON.iterate(o.columns, function(col){
        if (col.key !== 'domain') {
%}
                <option value="{%=col.key%}">{%=col.label%}</option>
{%
        }
    });
%}
            </select>
        </div>
        <div class="form-group">
            <select id="rule" class="form-control input-xsmall" onchange="domainsGrid.onSelectFilterRule()">
{%
    JSON.iterate(o.rules, function(rule){
%}
                <option value="{%=rule.key%}">{%#rule.label%}</option>
{%
    });
%}
            </select>
        </div>
        <div class="form-group filterOptions">
            <span class="filter-options filter-options-default">
                <input type="text" class="form-control input-xsmall" />
            </span>
            <span class="filter-options filter-options-range" style="display: none;">
                from <input type="text" class="form-control input-xsmall" name="from" />
                to <input type="text" class="form-control input-xsmall" name="to" />
            </span>
        </div>
        <button type="button" class="btn btn-success" onclick="domainsGrid.addFilter()">
            <i class="fa fa-plus"></i>
            Add
        </button>
    </form>
</script>

<script type="text/x-tmpl" id="tmpl-domains-filter-list">
<div class="filter-list-header">Applied filters:</div>
{%
    for(var i = 0; i < o.filters.length; i++) {
        var filter = o.filters[i];
        var col = JSON.getBy(o.columns, 'key', filter.col);
        var rule = JSON.getBy(o.rules, 'key', filter.rule);
        var filterStr;
        if (filter.rule == 'range') {
            filterStr = filter.options.from + ' &le; ' + col.label + ' &le; ' + filter.options.to;
        } else {
            filterStr = col.label + ' ' + rule.label + ' ' + filter.options;
        }
%}
    <div class="filter-list-item">
        <a class="font-red-thunderbird" href="javascript:;" onclick="domainsGrid.removeFilter({%=i%})"><i class="fa fa-remove"></i></a> {%#filterStr%}
    </div>
{%
    }
%}
</script>