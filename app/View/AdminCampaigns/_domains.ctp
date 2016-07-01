<div id="domains-report"></div>
<script type="text/x-tmpl" id="tmpl-domains-report">
<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
    <thead>
    <tr>
        <th rowspan="2">Domain</th>
        <th colspan="9" style="border-bottom: 1px solid #e7ecf1; text-align: center;">Tracker</th>
        <th colspan="3" style="border-bottom: 1px solid #e7ecf1; text-align: center;">PlugRush.com</th>
    </tr>
    <tr>
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
    for(var domain in o.data.domains) {
        var row = o.data.domains[domain];
%}
        <tr>
            <td>{%=domain%}</td>
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