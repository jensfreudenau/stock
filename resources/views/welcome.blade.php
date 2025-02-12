<x-layout>
    <x-header>
        <x-slot:title>{{__('In Stock')}}</x-slot:title>
    </x-header>
    <div x-data="{ portfolios: [] }" x-init="portfolios = await (await fetch('/portfolio/active_portfolios')).json()">
        <div class="overflow-x-hidden">
            <div class="flex flex-wrap min-w-0 p-4 gap-4">
                <template x-for="portfolio in portfolios" :key="portfolio.symbol">
                    <div class="md:w-32 lg:w-96 md:48">
                        <x-line-chart x-init="symbolid=portfolio.symbol">
                            <x-slot name="symbol">
                                <span x-html="portfolio.name.slice(0, 20)+ '...'"></span>
                            </x-slot>
                        </x-line-chart>
                        <div x-data="{ performance: [] }" x-init="performance = await (await fetch(`/statistic/sharePerformance/${portfolio.symbol}`)).json()">
                            <div class="flex space-x-4">
                                <table>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="py-4 px-6">Tag</td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.day"></td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.day_profit"></td>
                                    </tr>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="py-4 px-6">Woche</td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.week"></td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.week_profit"></td>
                                    </tr>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="py-4 px-6">Monat</td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.month"></td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.month_profit"></td>
                                    </tr>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="py-4 px-6">Overall</td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.overall"></td>
                                        <td class="py-4 px-6" x-money.de-DE.EUR="performance.overall_profit"></td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <div class="overflow-x-hidden">
        <div class="flex flex-wrap min-w-0 p-4 gap-8">
            <x-card-income :year="2025"></x-card-income>
            <x-card-income :year="2024"></x-card-income>
            <x-card-income :year="2023"></x-card-income>
        </div>
    </div>
</x-layout>
