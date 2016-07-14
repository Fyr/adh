<div id="domains-report"></div>
<script type="text/javascript">
var domainsGrid;
$(function(){
    // var formatPrice = function(val) {
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

    var columns = [
        {key: 'domain', label: 'Domain'},
        {key: 'Tracker.visits', label: 'Visits', format: 'int'},
        {key: 'Tracker.clicks', label: 'Clicks', format: 'int'},
        {key: 'Tracker.conversions', label: 'Conv.', format: 'int'},
        {key: 'Tracker.revenue', label: 'Rev.', render: formatPrice},
        {key: 'Tracker.cost', label: 'Cost', render: formatPrice},
        {key: 'Tracker.profit', label: 'Profit', render: formatColorPrice},
        {key: 'Tracker.cpv', label: 'CPV', render: formatPrice},
        {key: 'Tracker.ctr', label: 'CTR', format: 'percent'},
        {key: 'Tracker.roi', label: 'ROI', render: formatColorPercent},
        {key: 'Sources.plugrush.uniques', label: 'Uniques', format: 'int'},
        {key: 'Sources.plugrush.raws', label: 'Raws', format: 'int'},
        {key: 'Sources.plugrush.amount', label: 'Amount', render: formatPrice},
    ];
    domainsGrid = new TableGrid('#domains-report', columns);
    var renderHeader = domainsGrid.renderHeader;
    domainsGrid.renderHeader = function() {
        var html = Format.tag('tr', null,
            Format.tag('th', {colspan: 10, class: 'grid-x-header'}, 'Tracker') + Format.tag('th', {colspan: 3, class: 'grid-x-header'}, 'PlugRush.com')
        );
        return html + renderHeader();
    }

    domainsGrid.init();
});
</script>

<script type="text/x-tmpl" id="tmpl-domains-report">
<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
    <thead>
        <tr>
            <th colspan="10" style="">Tracker</th>
            <th colspan="3" style="border-bottom: 1px solid #e7ecf1; text-align: center;">PlugRush.com</th>
        </tr>
        <tr>
            <th>Domain</th>
            <th>Visits</th>
            <th>Clicks</th>
            <th>Conv.</th>
            <th>Rev.</th>
            <th>Cost</th>
            <th>Profit</th>
            <th>CPV</th>
            <th>CTR</th>
            <th>ROI</th>

            <th>Uniques</th>
            <th>Raws</th>
            <th>Cost</th>
        </tr>
    </thead>
    <tbody>
{%
    for(var i = 0; i < o.data.length; i++) {
        var row = o.data[i];
%}
        <tr>
            <td>{%=row.domain%}</td>
{%
        if (row.Tracker) {
%}
            <td align="right">{%=row.Tracker.visits%}</td>
            <td align="right">{%=row.Tracker.clicks%}</td>
            <td align="right">{%=row.Tracker.conversions%}</td>
            <td align="right">{%=Price.format(row.Tracker.revenue)%}</td>
            <td align="right">{%=Price.format(row.Tracker.cost)%}</td>
            <td align="right" class="{%=(row.Tracker.profit < 0) ? 'font-red-thunderbird' : 'font-green-jungle'%}">{%=Price.format(row.Tracker.profit)%}</td>
            <td align="right">{%=Price.format(row.Tracker.cpv)%}</td>
            <td align="right">{%=row.Tracker.ctr%}%</td>
            <td align="right">{%=row.Tracker.roi%}%</td>
{%
        } else {
%}
            <td colspan="9" align="center"> - no data - </td>
{%

        }
        if (row.Sources && row.Sources.plugrush) {
            var _row = row.Sources.plugrush;
%}
            <td align="right">{%=_row.uniques%}</td>
            <td align="right">{%=_row.raws%}</td>
            <td align="right">{%=Price.format(_row.amount)%}</td>
{%
        } else {
%}
            <td colspan="3" align="center"> - no data - </td>
{%
        }
%}
        </tr>
{%
    }
%}
    </tbody>
</table>
</script>