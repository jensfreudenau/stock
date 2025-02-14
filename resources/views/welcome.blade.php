<x-layout>
    <x-header>
        <x-slot:title>{{__('In Stock')}}</x-slot:title>
    </x-header>
    <div x-data="{ portfolios: [] }" x-init="portfolios = await (await fetch('/portfolio/active_portfolios')).json()">
        <div class="overflow-x-hidden">
            <div class="flex flex-wrap min-w-0 p-4 gap-4">
                <template x-for="portfolio in portfolios" :key="portfolio.symbol">
                    <div class="md:w-32 lg:w-96 md:48">
                        <h5 >
                            <a :href="`portfolio/show/${portfolio.symbol}`" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"  x-text="portfolio.symbol"> </a>
                        </h5>
                        <x-chart-line symbol="${portfolio.symbol}" x-model="portfolio.symbol"> </x-chart-line>
                        <div x-data="{ performance: [] }" x-init="performance = await (await fetch(`/statistic/sharePerformance/${portfolio.symbol}`)).json()">
                            <div class="flex space-x-4">
                                <table class="table-fixed border-separate -sm:hidden empty-cells-hidden mx-auto my-auto w-screen">
                                    <thead class="bg-gray-100 border-b text-sm dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <tr>
                                        <th class="dark:text-white text-sm py-2 px-3"></th>
                                        <th class="dark:text-white text-sm py-2 px-3">Profit</th>
                                        <th class="dark:text-white text-sm py-2 px-3">ges. Profit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="bg-gray-50 border-b text-sm dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="dark:text-white text-sm py-2 px-3">{{__('Tag')}}</td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.day"></td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.day_profit"></td>
                                    </tr>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="dark:text-white text-sm py-2 px-3">{{__('Woche')}}</td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.week"></td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.week_profit"></td>
                                    </tr>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="dark:text-white text-sm py-2 px-3">{{__('Monat')}}</td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.month"></td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.month_profit"></td>
                                    </tr>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="dark:text-white text-sm py-2 px-3">{{__('Overall')}}</td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.overall"></td>
                                        <td class="dark:text-white text-sm py-2 px-3" x-money.de-DE.EUR="performance.overall_profit"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <x-header>
        <x-slot:title>{{__('Gekauft / Verkauft')}}</x-slot:title>
    </x-header>
    <div class="overflow-x-hidden">
        <div class="flex flex-wrap min-w-0 p-4 gap-8">
            <x-card-income :year="2025"></x-card-income>
            <x-card-income :year="2024"></x-card-income>
            <x-card-income :year="2023"></x-card-income>
        </div>
    </div>
</x-layout>
