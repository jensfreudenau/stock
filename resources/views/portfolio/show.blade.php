<x-layout>

    <x-header>
        <x-slot:title>{{__('Portfolio')}}</x-slot:title>
    </x-header>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{$portfolio->name}}</h2>
    <h3 class="text-base  text-gray-900 dark:text-white sm:text-base">{{$portfolio->symbol}}</h3>
    <div class="mt-1 pt-1 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
        <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
            <div x-data="{ portfolio: [] }" x-init="portfolio = await (await fetch('/portfolio/details/{{$portfolio->id}}')).json()">
                <div class="rounded-lg mb-6 border border-slate-300 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <script>
                        window.id = @json($portfolio->id);
                    </script>
                    <x-table-profit symbol="${portfolio.id}" x-model="portfolio.id"></x-table-profit>
                </div>
                <section class="rounded-lg mb-6 border border-slate-300 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <div class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                        <div class="space-y-2 md:flex md:gap-2 md:space-y-0 ">
                            <div class="w-full min-w-0 flex-1 space-y-4 md:order-2">
                                <p class="font-normal text-gray-700 dark:text-gray-400" x-text="portfolio.company.name">Name</p>
                                <a href="#"
                                   class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                                   x-text="portfolio.description"></a>
                                <form class="pb-3" action="{{ route('portfolio.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="symbol" value="" :value="portfolio.symbol">
                                    <input type="hidden" name="portfolioId" value="" :value="portfolio.id">
                                    <button type="submit"
                                            class="block text-white bg-orange-500 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                                        {{__('update Firma')}}
                                    </button>
                                </form>
                            </div>
                            <div class=" gap-4 pr-4">
                                <div class=" font-bold tracking-tight text-gray-900 dark:text-white p-2">{{__('Sektor')}}
                                    <div class="font-normal text-gray-700 dark:text-gray-400"
                                         x-text="portfolio.company.sector"></div>
                                </div>
                                <div
                                    class=" font-bold tracking-tight text-gray-900 dark:text-white p-2">{{__('Country')}}
                                    <div class="font-normal text-gray-700 dark:text-gray-400"
                                         x-text="portfolio.company.country"></div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <form class="pb-3" action="{{ route('portfolio.deactivate') }}"
                                          method="POST">
                                        @csrf
                                        <input type="hidden" name="symbol" value="" :value="portfolio.symbol">
                                        <input type="hidden" name="active" value="" :value="portfolio.active">
                                        <button type="submit"
                                                class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                            <i class="fa-solid fa-x pr-3" style="color: #e01024;"></i>
                                            {{__('deaktivieren')}}
                                        </button>
                                    </form>
                                </div>
                                <x-chart-bar id="{{$portfolio->id}}" x-model="portfolio.id">
                                    {{$portfolio->id}}
                                </x-chart-bar>

                            </div>
                        </div>

                        <x-chart-line id="{{$portfolio->id}}" x-model="portfolio.id"></x-chart-line>
                    </div>
                </section>
                <div class="rounded-lg mb-6 border-slate-300 border bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl pb-4">{{__('Transaktionen')}}</h2>
                    <div class="shadow-md sm:rounded-lg" x-data="{ transactions: [] }" x-init="transactions = await (await fetch(`/transaction/transactionsBySymbol/{{$portfolio->symbol}}`)).json()">
                        <div class="flex space-x-4">
                            <table class="table-fixed text-sm text-left text-gray-500 dark:text-gray-400 -sm:hidden empty-cells-hidden mx-auto my-auto w-full">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="py-3 px-6">{{__('Anzahl')}}</th>
                                    <th class="py-3 px-6">{{__('Preis')}}</th>
                                    <th class="py-3 px-6">{{__('ges. Preis')}}</th>
                                    <th class="py-3 px-6">{{__('gekauft/verkauft')}}</th>
                                    <th class="py-3 px-6">{{__('am')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="transaction in transactions" :key="transaction.id">
                                    <template x-if="transaction.quantity">
                                        <tr class="bg-slate-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.quantity"></td>
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="transaction.price"></td>
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-money.de-DE.EUR="transaction.quantity * transaction.price"></td>
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.type"></td>
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="transaction.transaction_at"></td>
                                        </tr>
                                    </template>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg mb-6 border-slate-300 border bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                    <div x-data="{ stockDetails: [] }" x-init="stockDetails = await (await fetch(`/instock/details/{{$portfolio->symbol}}`)).json()">
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
                            <div class="rounded-lg   mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
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
            </div>
        </div>

</x-layout>
