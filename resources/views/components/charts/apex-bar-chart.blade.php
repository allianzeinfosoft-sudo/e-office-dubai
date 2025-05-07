<div id="{{ $elementId }}"></div>

@push('js')
<script>
    const chartColors = {
        column: {
        series1: '#826af9',
        series2: '#d2b0ff',
        bg: '#f8d3ff'
        }
    };

    const labelColor = '#6e6b7b';
    const legendColor = '#6e6b7b';
    const borderColor = '#e7eef7';

    const barChartEl = document.querySelector('#{{ $elementId }}');

    const barChartConfig = {
        chart: {
            height: 400,
            type: 'bar',
            stacked: true,
            parentHeightOffset: 0,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                columnWidth: '15%',
                colors: {
                    backgroundBarColors: Array({{ count($series) }}).fill(chartColors.column.bg),
                    backgroundBarRadius: 10
                }
            }
        },
        dataLabels: { enabled: false },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'start',
            labels: {
                colors: legendColor,
                useSeriesColors: false
            }
        },
        colors: [chartColors.column.series1, chartColors.column.series2],
        stroke: {
            show: true,
            colors: ['transparent']
        },
        grid: {
            borderColor: borderColor,
            xaxis: { lines: { show: true } }
        },
        series: {!! json_encode($series) !!},
        xaxis: {
        categories: {!! json_encode($categories) !!},
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
            style: {
                colors: labelColor,
                fontSize: '13px'
            }
        }
        },
        yaxis: {
        labels: {
            style: {
            colors: labelColor,
            fontSize: '13px'
            }
        }
        },
        fill: { opacity: 1 }
    };

    if (barChartEl !== null) {
        const barChart = new ApexCharts(barChartEl, barChartConfig);
        barChart.render();
    }
</script>
@endpush