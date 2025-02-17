<x-layout>
    <div class="mt-8 p-5 border">
    <x-header>
        <x-slot:title>{{__('In Stock')}}</x-slot:title>
    </x-header>
    <div x-data="{ portfolios: [] }" x-init="portfolios = await (await fetch('/portfolio/active_portfolios')).json()">
        <div class="overflow-x-hidden ">
            <div class="flex flex-wrap min-w-0 p-4 gap-4">
                <template x-for="portfolio in portfolios" :key="portfolio.symbol">
                    <div class="md:w-32 lg:w-96 md:48">
                        <h5 class="text-2xl font-extrabold" >
                            <a :href="`portfolio/show/${portfolio.symbol}`" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700" x-text="portfolio.symbol"> </a>
                        </h5>
                        <div class="shadow-md sm:rounded-lg mb-3">
                            <x-chart-line symbol="${portfolio.symbol}" x-model="portfolio.symbol"> </x-chart-line>
                        </div>
                        <div x-data="{ symbol: portfolio.symbol }" x-effect="window.symbol = symbol">
                            <x-table-profit ></x-table-profit>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <div class="mt-8">
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
        </div>
    </div>
</x-layout>
