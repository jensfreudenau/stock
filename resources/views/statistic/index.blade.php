<x-layout>
    <x-header>
        <x-slot:title>{{__('Statistik')}}</x-slot:title>
    </x-header>

    <h5 class="dark:text-white text-3xl mb-3">aktive Posten</h5>
    @foreach($activeSymbols as $activeSymbol)
        <h5 class="dark:text-white text-5xl mb-3">
            <a :href="`/portfolio/show/{{$activeSymbol['id']}}`" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700" >{{$activeSymbol['symbol']}}&nbsp;{!! Str::limit($activeSymbol['name'], 45, ' ...') !!} </a>
        </h5>
        <div class="rounded-lg mb-6 border-slate-300 border bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
            <div x-data="{ stockDetails: [] }" x-init="stockDetails = await (await fetch(`/statistic/active/{{$activeSymbol['symbol']}}`)).json()">
                <div class="flex pt-4 flex-wrap min-w-0 gap-4">
                    <div class="rounded-lg space-x-4 mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white"> {{__('gesamte Anzahl')}}</p>
                        <p class="dark:text-white text-8xl" x-text="stockDetails.remainingShares"></p>
                    </div>
                    <div class="rounded-lg space-x-4 mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white"> {{__('Profit')}}</p>
                        <p class="text-6xl"
                           :class="{ 'text-red-500' : parseInt(stockDetails.profitLoss) < 0 , 'text-green-400' : parseInt(stockDetails.profitLoss) >= 0}"
                           x-money.de-DE.EUR=" (stockDetails.profitLoss)">
                        </p>
                    </div>
                    <div class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white"> {{__('Wert')}}</p>
                        <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.currentValue"></p>

                        <p class="dark:text-white pt-2"> {{__('Kaufwert')}}</p>
                        <p class="dark:text-white text-3xl pl-4" x-money.de-DE.EUR="stockDetails.remainingShares * stockDetails.averagePurchasePrice"></p>

                    </div>
                    <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white text-2xl "> {{__('%')}}</p>
                        <p class=" text-7xl"
                           :class="{ 'text-red-500' : parseInt(stockDetails.percent) < 0 , 'text-green-400' : parseInt(stockDetails.percent) >= 0}"
                           x-text=" (stockDetails.percent)">
                        </p>
                    </div>
                    <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white text-2xl ">&empty; {{__('Kaufpreis')}}</p>
                        <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.averagePurchasePrice"></p>
                    </div>
                    <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white text-2xl "> {{__('heutiger Preis')}}</p>
                        <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.currentPrice"></p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="rounded-lg mb-6 border-slate-300 border bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
        <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
            <p class="dark:text-white text-2xl"> {{__('gegenwärtiger Profit')}}</p>
            <p class="text-7xl">{{$profitTemp}} </p>
        </div>
    </div>
    <h2 class="dark:text-white text-3xl pt-7 mb-3">ehemalige Posten</h2>
    @foreach($archivedSymbols as $archivedSymbol)
        <h5 class="dark:text-white text-5xl mb-3">
            <a :href="`/portfolio/show/{{$archivedSymbol['id']}}`" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700" >{{$archivedSymbol['symbol']}}&nbsp;{{$archivedSymbol['name']}} </a>
        </h5>
            <div class="rounded-lg mb-6 border-slate-300 border bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
            <div x-data="{ stockDetails: [] }" x-init="stockDetails = await (await fetch(`/statistic/archive/{{$archivedSymbol['symbol']}}`)).json()">
                <div class="flex pt-4 flex-wrap min-w-0 gap-4">
                    <div class="rounded-lg space-x-4 mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white"> {{__('Profit')}}</p>
                        <p class="text-6xl"
                           :class="{ 'text-red-500' : parseInt(stockDetails.profitLoss) < 0 , 'text-green-400' : parseInt(stockDetails.profitLoss) >= 0}"
                           x-money.de-DE.EUR=" (stockDetails.profitLoss)">
                        </p>
                    </div>

                    <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white text-2xl"> {{__('%')}}</p>
                        <p class=" text-7xl"
                           :class="{ 'text-red-500' : parseInt(stockDetails.percent) < 0 , 'text-green-400' : parseInt(stockDetails.percent) >= 0}"
                           x-text=" (stockDetails.percent)">
                        </p>
                    </div>
                    <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white text-2xl">&empty; {{__('Kaufpreis')}}</p>
                        <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.averagePurchasePrice"></p>

                    </div>
                    <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <p class="dark:text-white text-2xl">&empty; {{__('Verkaufspreis')}}</p>
                        <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.averageSellPrice"></p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="rounded-lg mb-6 border-slate-300 border bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
        <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
            <p class="dark:text-white text-2xl"> {{__('gesamt Profit')}}</p>
            <p class="text-7xl">{{$profit}} </p>
        </div>
    </div>
</x-layout>
