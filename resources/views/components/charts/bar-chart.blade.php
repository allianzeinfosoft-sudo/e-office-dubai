<div style="height: {{ $height }}">
    <canvas id="{{ $id }}" class="chartjs" data-height="{{ $height }}"></canvas>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartEl = document.getElementById('{{ $id }}');

        const purpleColor = '#836AF9',
            yellowColor = '#ffe800',
            cyanColor = '#28dac6',
            orangeColor = '#FF8132',
            orangeLightColor = '#FDAC34',
            oceanBlueColor = '#299AFF',
            greyColor = '#4F5D70',
            greyLightColor = '#EDF1F4',
            blueColor = '#2B9AFF',
            blueLightColor = '#84D0FF';

        let cardColor = '{{ $colors["cardColor"] ?? "#fff" }}',
            headingColor = '{{ $colors["headingColor"] ?? "#000" }}',
            labelColor = '{{ $colors["labelColor"] ?? "#888" }}',
            legendColor = '{{ $colors["legendColor"] ?? "#333" }}',
            borderColor = '{{ $colors["borderColor"] ?? "#eee" }}';

        if (chartEl) {
            new Chart(chartEl, {
                type: 'bar',
                data: {
                    labels: $labels,
                    datasets: [{
                        data: $data,
                        backgroundColor: '{{ $barColor }}',
                        borderColor: 'transparent',
                        maxBarThickness: 15,
                        borderRadius: { topRight: 15, topLeft: 15 }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 500 },
                    plugins: {
                        tooltip: {
                            rtl: {{ $isRtl ? 'true' : 'false' }},
                            backgroundColor: cardColor,
                            titleColor: headingColor,
                            bodyColor: legendColor,
                            borderWidth: 1,
                            borderColor: borderColor
                        },
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { color: borderColor, drawBorder: false },
                            ticks: { color: labelColor }
                        },
                        y: {
                            min: 0,
                            max: {{ $maxY }},
                            grid: { color: borderColor, drawBorder: false },
                            ticks: { stepSize: {{ $stepY }}, color: labelColor }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush