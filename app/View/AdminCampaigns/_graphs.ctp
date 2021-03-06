<div id="d_charts"></div>
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
                $('#charts, #d_charts').html('<div style="border: 1px solid #e5e5e5; height: 400px; margin-bottom: 20px;"></div>');
                renderCharts('#d_charts div', getChartsData(response.data.stats, 'd_'), 'Daily Traffic Chart');
                renderCharts('#charts div', getChartsData(response.data.stats, ''), 'Summary Traffic Chart');
                renderReports(response);
            }
        });
        return true;
    } else {
        $('#d_charts, #summary-report, #domains-report').html('<div style="margin: 30px 0; text-align: center;">- Please, select campaigns -</div>');
        $('#charts').html('<div />');
        return false;
    }
}

function renderReports(response) {
    $('#summary-report').html(Tmpl('summary-report').render(response.data.stats));
    aData = [];
    for(var domain_id in response.data.domainStats) {
        row = response.data.domainStats[domain_id];
        row.domain_id = domain_id;
        row.domain = response.data.domains[domain_id];
        row.epv = parseFloat(row.epv);
        aData.push(row);
    };
    domainsGrid.setData(aData);
    domainsGrid.render();
    // domainsGrid.initFilter();
}

function getChartsData(stats, d_) {
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

        data.xAxis.push(moment(date).format('MMM D'));

        data.visits.push(parseInt(stats[date][d_ + 'src_visits']));
        data.clicks.push(parseInt(stats[date][d_ + 'trk_clicks']));
        data.conversion.push(parseFloat(stats[date][d_ + 'conversion']));

        data.cost.push(parseFloat(stats[date][d_ + 'cost']));
        data.revenue.push(parseFloat(stats[date][d_ + 'revenue']));
        data.profit.push(parseFloat(stats[date][d_ + 'profit']));

        data.ctr.push(parseInt(stats[date][d_ + 'ctr']));
        data.roi.push(parseInt(stats[date][d_ + 'roi']));

        data.epv.push(parseFloat(stats[date][d_ + 'epv']));
    }
    return data;
}

function renderCharts(e, data, title) {
    var options = {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: title,
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
    $(e).highcharts(options);
}
</script>
