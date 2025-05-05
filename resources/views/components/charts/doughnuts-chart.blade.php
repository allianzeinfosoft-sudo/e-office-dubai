<div style="height: {{ $height }}">
    <canvas id="{{ $id }}" class="chartjs" data-height="{{ $height }}"></canvas>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartEl = document.getElementById('{{ $id }}');

        const cardColor = '{{ $colors["cardColor"] ?? "#fff" }}';
        const headingColor = '{{ $colors["headingColor"] ?? "#000" }}';
        const labelColor = '{{ $colors["labelColor"] ?? "#888" }}';
        const legendColor = '{{ $colors["legendColor"] ?? "#333" }}';
        const borderColor = '{{ $colors["borderColor"] ?? "#eee" }}';

        if (chartEl) {
            new Chart(chartEl, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        data: {!! json_encode($donutsData) !!},
                        backgroundColor: {!! json_encode($backgroundColors) !!},
                        borderWidth: 0,
                        pointStyle: 'rectRounded'
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '68%',
                    animation: { duration: 500 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ' ' + context.label + ' : ' + context.parsed + ' %';
                                }
                            },
                            rtl: {{ $isRtl ? 'true' : 'false' }},
                            backgroundColor: cardColor,
                            titleColor: headingColor,
                            bodyColor: legendColor,
                            borderWidth: 1,
                            borderColor: borderColor
                        }
                    }
                }
            });
        }
    });
</script>
@endpush