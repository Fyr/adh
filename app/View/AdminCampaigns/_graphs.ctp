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
                renderCharts(getChartsData(response.data.stats.byHours));
                renderReports(response);

            }
        });
    } else {
        $('#charts, #summary-report, #domains-report').html('<div style="margin: 30px 0; text-align: center;">- Please, select campaigns -</div>');
    }
}

function renderReports(response) {
    $('#summary-report').html(Tmpl('summary-report').render(response.data.stats.byDates));
    //domainsGrid.setData(response.data.domains);
    //domainsGrid.render();
    // domainsGrid.initFilter();
}

function getChartsData(stats) {
    var data = {};
    data.xAxis = [];

    data.visits = [];
    data.clicks = [];
    data.conversion = [];

    data.cost = [];
    data.revenue = [];
    data.profit = [];

    data.ctr = [];
    data.roi = [];
    data.epv = [];

    data.startDate = '';
    data.endDate = '';
    for(var date in stats) {
        if (!data.startDate) {
            data.startDate = moment(date);
        }
        data.endDate = moment(date);

        data.xAxis.push(date);

        data.visits.push(parseInt(stats[date].src_visits));
        data.clicks.push(parseInt(stats[date].trk_clicks));
        data.conversion.push(parseFloat(stats[date].conversion));

        data.cost.push(parseFloat(stats[date].cost));
        data.revenue.push(parseFloat(stats[date].revenue));
        data.profit.push(parseFloat(stats[date].profit));

        data.ctr.push(parseInt(stats[date].ctr));
        data.roi.push(parseInt(stats[date].roi));

        data.epv.push(parseFloat(stats[date].epv));
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
            text: data.startDate.format('MMM D, YYYY') + ' - ' + data.endDate.format('MMM D, YYYY'),
            x: -20
        },
        xAxis: {
            categories: data.xAxis
        },
        yAxis: [{
            title: {
                text: 'Visitors',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            min: 0
        }, {
            title: {
                text: 'Clicks/Conversion',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            min: 0
        }, {
            title: {
                text: 'CTR/ROI',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            min: -100,
            max: 100,
            opposite: true,
            labels: {
                format: '{value}%',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
        }, {
            title: {
                text: 'Cost/Revenue/Profit',
                style: {
                    color: Highcharts.getOptions().colors[3]
                }
            },
            opposite: true,
            labels: {
                format: '${value}',
                style: {
                    color: Highcharts.getOptions().colors[3]
                }
            },
        }, {
            title: {
                text: 'EPV',
                style: {
                    color: Highcharts.getOptions().colors[8]
                }
            },
            opposite: true,
            labels: {
                format: '${value}',
                style: {
                    color: Highcharts.getOptions().colors[8]
                }
            }
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            borderWidth: 0,
        },
        series: [{
            name: 'Visits',
            data: data.visits,
            yAxis: 0,
        }, {
            name: 'Clicks',
            data: data.clicks,
            yAxis: 1,
        }, {
            name: 'Conversion',
            data: data.conversion,
            yAxis: 1,
        }, {
            name: 'CTR',
            data: data.ctr,
            yAxis: 2,
            type: 'spline',
            tooltip: {
                valueSuffix: '%'
            }
        }, {
            name: 'ROI',
            data: data.roi,
            yAxis: 2,
            type: 'spline',
            tooltip: {
                valueSuffix: '%'
            }
        }, {
            name: 'Cost',
            data: data.cost,
            yAxis: 3,
            type: 'column',
            tooltip: {
                valuePrefix: '$'
            }
        }, {
            name: 'Revenue',
            data: data.revenue,
            yAxis: 3,
            type: 'column',
            tooltip: {
                valuePrefix: '$'
            }
        }, {
            name: 'Profit',
            data: data.profit,
            yAxis: 3,
            type: 'column',
            tooltip: {
                valuePrefix: '$'
            }
        }, {
            name: 'EPV',
            data: data.epv,
            yAxis: 4,
            tooltip: {
                valuePrefix: '$'
            }
        }],
        credits: {
            enabled: false
        },
    };
    $('#charts div').highcharts(options);
}
</script>
