<x-layout>

    <x-header>
        <x-slot:title>{{__('Portfolio')}}</x-slot:title>
    </x-header>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{$symbol}}</h2>
    <div class="mt-1 pt-1 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
        <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
            <div x-data="{ portfolio: [] }" x-init="portfolio = await (await fetch('/portfolio/details/{{$symbol}}')).json()">

                <div class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <div x-data="{ performance: [] }" x-init="performance = await (await fetch(`/statistic/sharePerformance/{{$symbol}}`)).json()">
                        <div class="flex space-x-4">
                            <table class="table-fixed border-separate -sm:hidden empty-cells-hidden mx-auto my-auto w-screen dark:bg-gray-700">
                                <thead class="bg-gray-100 border-b text-sm dark:bg-gray-800 dark:border-white hover:bg-gray-100 dark:hover:bg-gray-600">
                                <tr>
                                    <th class="dark:text-white text-lg py-2 px-3"></th>
                                    <th class="dark:text-white text-lg py-2 px-3">Profit</th>
                                    <th class="dark:text-white text-lg py-2 px-3">ges. Profit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="bg-gray-50 border-b text-lg dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="dark:text-white text-lg py-2 px-3">{{__('Tag')}}</td>
                                    <td class="text-lg py-2 px-3"
                                        :class="{ 'text-red-500' : parseInt(performance.day) < 0 , 'text-green-400' : parseInt(performance.day) > 0}"
                                        x-money.de-DE.EUR="performance.day">

                                    </td>
                                    <td class="text-lg py-2 px-3"
                                        :class="{ 'text-red-500' : parseInt(performance.day_profit) < 0 , 'text-green-400' : parseInt(performance.day_profit) > 0}"
                                        x-money.de-DE.EUR="performance.day_profit">

                                    </td>
                                </tr>
                                <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="dark:text-white text-lg py-2 px-3">{{__('Woche')}}</td>
                                    <td class="text-lg py-2 px-3"
                                        :class="{ 'text-red-500' : parseInt(performance.week) < 0 , 'text-green-400' : parseInt(performance.week) > 0}"
                                        x-money.de-DE.EUR="performance.week">

                                    </td>
                                    <td class="text-lg py-2 px-3"
                                        :class="{ 'text-red-500' : parseInt(performance.week_profit) < 0 , 'text-green-400' : parseInt(performance.week_profit) > 0}"
                                        x-money.de-DE.EUR="performance.week_profit">

                                    </td>
                                </tr>
                                <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="dark:text-white text-lg py-2 px-3">{{__('Monat')}}</td>
                                    <td class="text-lg py-2 px-3"
                                        :class="{ 'text-red-500' : parseInt(performance.month) < 0 , 'text-green-400' : parseInt(performance.month) > 0}"
                                        x-money.de-DE.EUR="performance.month">
                                    </td>
                                    <td class="text-lg py-2 px-3"
                                        :class="{ 'text-red-500' : parseInt(performance.month_profit) < 0 , 'text-green-400' : parseInt(performance.month_profit) > 0}"
                                        x-money.de-DE.EUR="performance.month_profit">
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl pb-4">{{__('Transaktionen')}}</h2>
                    <div x-data="{ transactions: [] }" x-init="transactions = await (await fetch(`/transaction/transactionsBySymbol/{{$symbol}}`)).json()">
                        <div class="flex space-x-4">
                            <table class="mb-6 table-fixed border-separate -sm:hidden empty-cells-hidden mx-auto my-auto w-screen dark:bg-gray-700">
                                <thead class="bg-gray-100 border-b text-sm dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <tr>
                                    <th class="dark:text-white text-lg py-2 px-3">{{__('Anzahl')}}</th>
                                    <th class="dark:text-white text-lg py-2 px-3">{{__('Preis')}}</th>
                                    <th class="dark:text-white text-lg py-2 px-3">{{__('ges. Preis')}}</th>
                                    <th class="dark:text-white text-lg py-2 px-3">{{__('gekauft/verkauft')}}</th>
                                    <th class="dark:text-white text-lg py-2 px-3">{{__('am')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="transaction in transactions" :key="transaction.id">
                                    <template x-if="transaction.quantity">
                                        <tr class="bg-gray-50 border-b text-sm dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <td class="dark:text-white text-lg py-2 px-3" x-text="transaction.quantity"></td>
                                            <td class="dark:text-white text-lg py-2 px-3" x-money.de-DE.EUR="transaction.price"></td>
                                            <td class="dark:text-white text-lg py-2 px-3" x-money.de-DE.EUR="transaction.quantity * transaction.price"></td>
                                            <td class="dark:text-white text-lg py-2 px-3" x-text="transaction.type"></td>
                                            <td class="dark:text-white text-lg py-2 px-3" x-text="transaction.transaction_at"></td>
                                        </tr>
                                    </template>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <div x-data="{ stockDetails: [] }" x-init="stockDetails = await (await fetch(`/instock/details/{{$symbol}}`)).json()">
                            <div class="flex pt-4 flex-wrap min-w-0 gap-4">
                                <div class="rounded-lg space-x-4 mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <p class="dark:text-white"> {{__('gesamte Anzahl')}}</p>
                                    <p class="dark:text-white text-8xl" x-text="stockDetails.remainingShares"></p>
                                </div>
                                <div class="rounded-lg  space-x-4  mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <p class="dark:text-white"> {{__('Profit')}}</p>
                                    <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.profitLoss"></p>
                                </div>
                                <div class="rounded-lg  space-x-4  mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <p class="dark:text-white "> {{__('Wert')}}</p>
                                    <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.currentValue"></p>
                                </div>
                                <div class="rounded-lg  space-x-4  mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <p class="dark:text-white text-2xl "> {{__('%')}}</p>
                                    <p class=" text-7xl"
                                       :class="{ 'text-red-500' : parseInt(stockDetails.percent) < 0 , 'text-green-400' : parseInt(stockDetails.percent) >= 0}"
                                       x-text=" (stockDetails.percent)">
                                    </p>
                                </div>
                                <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <p class="dark:text-white text-2xl "> {{__('Kaufpreis')}}</p>
                                    <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.averagePurchasePrice"></p>
                                </div>
                                <div class="rounded-lg space-x-4 mb-3 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <p class="dark:text-white text-2xl "> {{__('heutiger Preis')}}</p>
                                    <p class="dark:text-white text-5xl" x-money.de-DE.EUR="stockDetails.currentPrice"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <div class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <div class="space-y-2 md:flex md:gap-2 md:space-y-0 ">
                            <div class="w-full min-w-0 flex-1 space-y-4 md:order-2">
                                <p class="font-normal text-gray-700 dark:text-gray-400"
                                   x-text="portfolio.name"></p>
                                <p class="font-normal text-gray-700 dark:text-gray-400"></p>
                                <a href="#"
                                   class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                                   x-text="portfolio.description"></a>
                                <form class="pb-3" action="{{ route('portfolio.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="symbol" value="" :value="portfolio.symbol">
                                    <button type="submit"
                                            class="block text-white bg-orange-500 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                                        {{__('update Firma')}}
                                    </button>
                                </form>
                            </div>
                            <div class=" gap-4 pr-4">
                                <div
                                    class=" font-bold tracking-tight text-gray-900 dark:text-white p-2">{{__('Sektor')}}
                                    <div class="font-normal text-gray-700 dark:text-gray-400"
                                         x-text="portfolio.sector"></div>
                                </div>
                                <div
                                    class=" font-bold tracking-tight text-gray-900 dark:text-white p-2">{{__('Country')}}
                                    <div class="font-normal text-gray-700 dark:text-gray-400"
                                         x-text="portfolio.country"></div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <form class="pb-3" action="{{ route('portfolio.deactivate') }}"
                                          method="POST">
                                        @csrf
                                        <input type="hidden" name="symbol" value="" :value="portfolio.symbol">
                                        <input type="hidden" name="active" value="" :value="portfolio.active">
                                        <button type="submit"
                                                class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                            <svg class="me-1.5 h-5 w-5" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18 17.94 6M18 18 6.06 6"/>
                                            </svg>
                                            {{__('deaktivieren')}}
                                        </button>
                                    </form>
                                </div>

                                <x-chart-bar symbol="{{$symbol}}" x-model="portfolio.symbol">
                                    {{$symbol}}
                                </x-chart-bar>
                            </div>
                        </div>
                        <x-chart-line symbol="{{$symbol}}" x-model="portfolio.symbol"></x-chart-line>
                    </div>
                </section>
            </div>
        </div>
    </div>

</x-layout>
