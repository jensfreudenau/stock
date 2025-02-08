<x-layout>
    <x-header>
        <x-slot:title>{{__('In Stock')}}</x-slot:title>
    </x-header>
    <div x-data="{ userId: 123, fetchData() {
    fetch(`https://api.example.com/user/${this.userId}`)
        .then(response => response.json())
        .then(data => console.log(data));
}}">
        <button @click="fetchData()">Daten abrufen</button>
    </div>
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
                                <p>Tag<span x-text="performance.day"></span></p>
                                <p>Woche<span x-text="performance.week"></span></p>
                                <p>Monat<span x-text="performance.month"></span></p>
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
