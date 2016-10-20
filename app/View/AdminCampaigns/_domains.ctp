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
        {key: 'src_visits', label: 'Visits', render: function(val, row){
            return Format.tag('td', {align: 'right'}, row['src_visits'] + ' (' + row['trk_visits'] + ')');
        }},
        {key: 'trk_clicks', label: 'Clicks', render: function(val, row){
            return Format.tag('td', {align: 'right'}, row['trk_clicks'] + ' (' + row['src_clicks'] + ')');
        }},
        {key: 'conversion', label: 'Conv.', format: 'int'},
        {key: 'revenue', label: 'Rev.', render: formatPrice},
        {key: 'cost', label: 'Cost', render: formatPrice},
        {key: 'profit', label: 'Profit', render: formatColorPrice},
        {key: 'cpv', label: 'CPV', render: formatPrice},
        {key: 'ctr', label: 'CTR', format: 'percent'},
        {key: 'roi', label: 'ROI', render: formatColorPercent},
        {key: 'epv', label: 'EPV', render: function(val, row){
            return Format.tag('td', {align: 'right'}, Price.format(row['epv']) + ' (' + Price.format(row['trk_epv']) + ')');
        }}
    ];

    domainsGrid = new DomainListGrid();
    domainsGrid.init('#domains-report', columns, null, {
        checkboxes: true,
        primaryKey: 'domain_id',
        rowActions: function(id, row) {
            var html;
            html = Format.tag('a', {
                class: 'btn btn-icon-only action-icon green',
                href: 'javascript:;',
                title: 'Remove from local black list'
            }, '<i class="fa fa-play"></i>');

            html+= Format.tag('a', {
                class: 'btn btn-icon-only action-icon red',
                href: 'javascript:;',
                title: 'Add to local black list'
            }, '<i class="fa fa-remove"></i>');
            return html;
        }
    });
});
</script>

<script type="text/x-tmpl" id="tmpl-domains-filter">
    <form id="domainFilter" class="form-inline">
        <div class="form-group" style="{%=(o.filters.length ? '' : 'display: none')%}">
            <select id="filterOper" class="form-control input-xsmall">
                <option value="and">AND</option>
                <option value="or">OR</option>
            </select>
        </div>
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
                <input type="text" class="form-control input-xsmall" onkeyup="$(this).parent().parent().removeClass('has-error')"/>
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
        var filterStr = (i > 0) ? filter.oper.toUpperCase() + ' ' : '';
        filterStr+= col.label + ' ' + rule.label + ' ' + filter.options;
%}
    <div class="filter-list-item">
        <a class="font-red-thunderbird" href="javascript:;" onclick="domainsGrid.removeFilter({%=i%})"><i class="fa fa-remove"></i></a> {%#filterStr%}
    </div>
{%
    }
%}
</script>