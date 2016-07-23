<div id="charts"></div>
<script type="text/javascript">
function updateCharts() {
    var vals = [];
    $('.dataTable td.checkboxes input[type=checkbox]:checked').each(function(){
        vals.push($(this).val());
    });
    if (vals.length) {
        $('#ajax-loader').show();
        var url = '<?=$this->Html->url(array('controller' => 'AdminAjax', 'action' => 'getStats', 'ext' => 'json'))?>';
        $.post(url, {data: {ids: vals, from: $('#FilterFrom').val(), to: $('#FilterTo').val()}}, function (response) {
            $('#ajax-loader').hide();
            if (checkJson(response)) {
                $('#charts').html('<div style="border: 1px solid #e5e5e5; height: 400px;"></div>');
                renderCharts(getChartsData(response.data.traffic));
                renderReports(response);

            }
        });
    } else {
        $('#charts, #summary-report, #domains-report').html('<div style="margin: 30px 0; text-align: center;">- Please, select campaigns -</div>');
    }
}

function renderReports(response) {
    $('#summary-report').html(Tmpl('summary-report').render(response));
    domainsGrid.setData(response.data.domains);
    domainsGrid.render();
    // domainsGrid.initFilter();
}

function getChartsData(stats) {
    data = {};
    data.xAxis = [];
    data.amount = [];
    data.raws = [];
    data.uniques = [];
    data.startDate = '';
    data.endDate = '';
    for(var date in stats) {
        if (!data.startDate) {
            data.startDate = date;
        }
        data.endDate = date;

        data.xAxis.push(date);
        data.amount.push(stats[date].amount);
        data.raws.push(stats[date].raws);
        data.uniques.push(stats[date].uniques);
    }
    return data;
}

function renderCharts(data) {
    var options = {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Summary Traffic Chart',
            x: -20 //center
        },
        subtitle: {
            text: data.startDate + ' - ' + data.endDate,
            x: -20
        },
        xAxis: {
            categories: data.xAxis
        },
        yAxis: [
            {
                title: {
                    text: 'Visitors',
                },
                min: 0
            },
            {
                title: {
                    text: 'Cost',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min: 0,
                opposite: true,
                labels: {
                    format: '${value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
            }
        ],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            borderWidth: 0,
        },
        series: [
            {
                name: 'Cost',
                data: data.amount,
                yAxis: 1,
                type: 'column',
                tooltip: {
                    valuePrefix: '$'
                }
            },
            {
                name: 'Uniques',
                data: data.uniques,
                yAxis: 0,
                type: 'spline'
            },
            {
                name: 'Raws',
                data: data.raws,
                yAxis: 0,
                type: 'spline',

            }
        ],
        credits: {
            enabled: false
        },
    };
    $('#charts div').highcharts(options);
}
</script>
