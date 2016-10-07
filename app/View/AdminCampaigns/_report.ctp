<div id="summary-report"></div>
<script type="text/x-tmpl" id="tmpl-summary-report">
<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
    <thead>
    <tr>
        <th>Date</th>
        <th>LP Visits</th>
        <th>LP Clicks</th>
        <th>Converion</th>
        <th>Cost</th>
        <th>Revenue</th>
        <th>Profit</th>
        <th>CPV</th>
        <th>CTR</th>
        <th>ROI</th>
        <th>EPV</th>
    </tr>
    </thead>
    <tbody>
{%
    for(var date in o) {
        var row = o[date];
%}
        <tr>
            <td>{%=date%}</td>
            <td align="right">{%=row.src_visits%} ({%=row.trk_visits%})</td>
            <td align="right">{%=row.trk_clicks%} ({%=row.src_clicks%})</td>
            <td align="right">{%=row.conversion%}</td>
            <td align="right">{%=Price.format(row.cost)%}</td>
            <td align="right">{%=Price.format(row.revenue)%}</td>
            <td align="right">{%=Price.format(row.profit)%}</td>
            <td align="right">{%=Price.format(row.cpv)%}</td>
            <td align="right">{%=row.ctr%}%</td>
            <td align="right">{%=row.roi%}%</td>
            <td align="right">{%=Price.format(row.epv)%} ({%=Price.format(row.trk_epv)%})</td>
        </tr>
{%
    }
%}

    </tbody>
</table>
</script>