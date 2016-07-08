<div id="domains-report"></div>
<script type="text/javascript">
var domainsGrid = null;
$(function(){
    var SimpleGrid = function(container, columns, data) {
        var self = this;
        var $self = $(container);

        self.columns = columns;
        self.data = data;
        self.sortBy = null;
        self.sortDesc = false;

        this.setData = function(data) {
            self.data = data;
            /*
            for(var i = 0; i < count(self.data); i++) {
                self.data[i].domain
                self.data[i].toString = function(){
                    return this[self.sortBy];
                }
            }
            */
        };

        this.sortBy = function(col, lDesc) {
            // self.sortCol
            // TODO внедрить в self.data fn toString - нужна для сортировки
            // toString должна возвращать значение колонки по которой сортируют
            self.data.sort();
            if (lDesc) {
                self.data.reverse();
            }
        }

        this.render = function() {
            /*
            this.renderHeader(columns);
            this.renderBody(columns, data);
            */

            $self.html(Tmpl('tmpl-domains-report').render(self.data));
        };
    };


    domainsGrid = new SimpleGrid('#domains-report');
});
</script>
<script type="text/x-tmpl" id="tmpl-simple-grid-layout">
<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
    <thead>
        {% include('tmpl-simple-grid-header'); %}
    </thead>
    <tbody>
        {% include('tmpl-simple-grid-body'); %}
    </tbody>
</table>
</script>
<script type="text/x-tmpl" id="tmpl-simple-grid-header">
<tr>
    <th colspan="10" style="border-bottom: 1px solid #e7ecf1; text-align: center;">Tracker</th>
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
</script>
<script type="text/x-tmpl" id="tmpl-simple-grid-body">
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
</script>

<script type="text/x-tmpl" id="tmpl-domains-report">
<table class="table table-striped table-bordered table-hover table-header-fixed dataTable">
    <thead>
        <tr>
            <th colspan="10" style="border-bottom: 1px solid #e7ecf1; text-align: center;">Tracker</th>
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