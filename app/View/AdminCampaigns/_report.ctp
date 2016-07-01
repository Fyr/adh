<div id="summary-report"></div>
<script type="text/x-tmpl" id="tmpl-summary-report">
<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
    <thead>
    <tr>
        <th>Date</th>
        <th>Uniques</th>
        <th>Raws</th>
        <th>Cost</th>
    </tr>
    </thead>
    <tbody>
{%
    var total = {uniques: 0, raws: 0, amount: 0};
    for(var date in o.data.traffic) {
        var row = o.data.traffic[date];
        for(var i in total) {
            total[i]+= row[i];
        }
%}
        <tr>
            <td>{%=date%}</td>
            <td align="right">{%=row.uniques%}</td>
            <td align="right">{%=row.raws%}</td>
            <td align="right">{%=Price.format(row.amount)%}</td>
        </tr>
{%
    }
%}
        <tr style="font-weight: bold;">
            <td align="right">Total: </td>
{%
    total.amount = Price.format(total.amount);
    for(var i in total) {
%}
            <td align="right">{%=total[i]%}</td>
{%
    }
%}
        </tr>
    </tbody>
</table>
</script>