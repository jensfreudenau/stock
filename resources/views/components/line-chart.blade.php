@props(["symbolid" => ""])
<div  {{$attributes}} x-data="{ symbolid: '{{ $symbolid }}' }">
    <div x-id="['chart']">
        <div x-init="init($id('chart'), $data)" class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-xl font-bold text-gray-900 dark:text-white title">{{ $symbol }} </h5>
            <canvas :id="$id('chart')" >{{ $symbol }}</canvas>
        </div>
    </div>
</div>

<script>

    function init($id,{ symbolid }) {
        fetch(`/statistic/chart/` + symbolid)
            .then(res => res.json())
            .then(data => {
                this.isLoading = false;
                initChart(data, $id)
            });
    }
    function initChart(data, $id) {
        const ctx = document.getElementById($id);
        new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    data: data,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            display: false
                        }
                    }
                },
                responsive: true,
                elements: {
                    point:{
                        radius: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>

