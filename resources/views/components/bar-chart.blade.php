@props(["symbolid" => ""])
<div  {{$attributes}} x-data="{ symbolid: '{{ $symbolid }}' }">
    <div x-id="['bar-chart']">
        <div x-init="initBarChart($id('bar-chart'), $data)" class="my-4 w-full p-2 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-4 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-l font-bold text-gray-900 dark:text-white title">{{ $symbol }} </h5>
            <canvas :id="$id('bar-chart')" >{{ $symbol }}</canvas>
    </div>
    </div>
</div>
<script>
    function initBarChart($id, { symbolid }) {
        fetch(`/portfolio/analytics/` + symbolid)
            .then(res => res.json())
            .then(data => {
                this.isLoading = false;
                barChart(data, $id)
            });
    }
    function barChart(data, $id) {
        let ctc = document.getElementById($id);
        let datas = {
            labels: ['strong buy', 'buy', 'hold', 'sell', 'strong sell'],
            datasets: [{
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                data: [
                    data[0]['analyst_rating_strong_buy'],
                    data[0]['analyst_rating_buy'],
                    data[0]['analyst_rating_hold'],
                    data[0]['analyst_rating_sell'],
                    data[0]['analyst_rating_strong_sell']
                    ]
            }]
        };
        new Chart(ctc, {
            type: 'bar',
            data: datas,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
