<div x-id="['chart']">
    <div x-data="{ id: {{ $id }} }">
        <div x-init='$nextTick(() => {initBarChart($id("chart"), `${id}`)})'>
            <h5 class="mb-2 text-l font-bold text-gray-900 dark:text-white title"> </h5>
            <canvas  :id="$id('chart')" id="1" > </canvas>
        </div>
    </div>
</div>

