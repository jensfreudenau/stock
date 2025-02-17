<div x-data="{ symbol: window.symbol }">
    <div class="shadow-md sm:rounded-lg" x-data="{ performance: [] }" x-init="performance = await (await fetch(`/statistic/sharePerformance/${symbol}`)).json()">
     

    <div class="flex space-x-4">
        <table class="table-fixed text-sm text-left text-gray-500 dark:text-gray-400 -sm:hidden empty-cells-hidden mx-auto my-auto w-full">
        <thead class="text-xs  text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="py-3 px-6"></th>
            <th class="py-3 px-6">Profit</th>
            <th class="py-3 px-6">ges. Profit</th>
        </tr>
        </thead>
        <tbody>
        <tr class="bg-slate-100 border-b  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
            <th class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{__('Tag')}}</th>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.day"></td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.day_profit"></td>
        </tr>
        <tr class="bg-slate-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{__('Woche')}}</td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.week"></td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.week_profit"></td>
        </tr>
        <tr class="bg-slate-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{__('Monat')}}</td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.month"></td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.month_profit"></td>
        </tr>
        <tr class="bg-slate-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{__('Overall')}}</td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.overall"></td>
            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="performance.overall_profit"></td>
        </tr>
        </tbody>
    </table>
</div>
</div>



</div>
