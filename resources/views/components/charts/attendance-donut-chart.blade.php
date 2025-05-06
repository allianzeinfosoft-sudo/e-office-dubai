<div id="{{ $id }}" style="min-height: {{ $height }}"></div>

@push('js')
<script>
(function() {
  const chartLabels_{{ $id }} = @json($labels);
  const chartData_{{ $id }} = @json($donutsData);
  const chartColors_{{ $id }} = @json($backgroundColors);

  const config_{{ $id }} = {
    colors: {
      cardColor: '#fff',
      headingColor: '#5e5873',
      textMuted: '#b9b9c3',
      bodyColor: '#6e6b7b',
      borderColor: '#ebe9f1'
    },
    colors_dark: {
      cardColor: '#2b2c40',
      headingColor: '#ccc',
      textMuted: '#777',
      bodyColor: '#999',
      borderColor: '#444'
    }
  };

  const isDark_{{ $id }} = document.documentElement.classList.contains('dark');

  let cardColor, headingColor, labelColor, borderColor, legendColor;
  if (isDark_{{ $id }}) {
    cardColor = config_{{ $id }}.colors_dark.cardColor;
    headingColor = config_{{ $id }}.colors_dark.headingColor;
    labelColor = config_{{ $id }}.colors_dark.textMuted;
    legendColor = config_{{ $id }}.colors_dark.bodyColor;
    borderColor = config_{{ $id }}.colors_dark.borderColor;
  } else {
    cardColor = config_{{ $id }}.colors.cardColor;
    headingColor = config_{{ $id }}.colors.headingColor;
    labelColor = config_{{ $id }}.colors.textMuted;
    legendColor = config_{{ $id }}.colors.bodyColor;
    borderColor = config_{{ $id }}.colors.borderColor;
  }

  const donutChartEl = document.querySelector('#{{ $id }}');
  const donutChartConfig = {
    chart: {
      height: '{{ $height }}',
      type: 'donut'
    },
    labels: chartLabels_{{ $id }},
    series: chartData_{{ $id }},
    colors: chartColors_{{ $id }},
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        return parseInt(val, 10) + '%';
      }
    },
    legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: '{{ $isRtl ? 'right' : 'left' }}',
      labels: {
        colors: legendColor
      }
    },
    plotOptions: {
      pie: {
        donut: {
          labels: {
            show: true,
            name: {
              fontSize: '1.2rem'
            },
            value: {
              fontSize: '1rem',
              formatter: val => parseInt(val, 10) + '%'
            },
            total: {
              show: true,
              label: 'Total',
              formatter: function (w) {
                return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' Days';
              }
            }
          }
        }
      }
    }
  };

  if (donutChartEl) {
    const donutChart = new ApexCharts(donutChartEl, donutChartConfig);
    donutChart.render();
  }
})();
</script>
@endpush