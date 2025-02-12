<div class="overflow-x-auto relative sm:rounded-lg " x-data="{ stocks: [] }" x-init="stocks = await (await fetch('/transaction')).json()">
    <div class="pb-4 bg-white dark:bg-gray-900">
        <label for="table-search" class="sr-only">Search</label>
        <div class="relative mt-8 flex flex-col md:flex-row gap-8 mb-4">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"></path>
                </svg>
            </div>
            <input type="text" id="table-search"
                   @input = "stocks = $event.target.value ? stocks.filter(stock => stock.symbol.toLowerCase().includes($event.target.value.toLowerCase())) : await (await fetch('/instock')).json()"
                   class="ml-2 block p-2 pl-10 w-80 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Suche">
            <button data-modal-target="buy-modal" data-modal-toggle="buy-modal" class="block text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" type="button">
                {{__('Aktien hinzufügen')}}
            </button>
            <button data-modal-target="sell-modal" data-modal-toggle="sell-modal" class="block text-white bg-orange-500 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800" type="button">
                {{__('Aktien verkaufen')}}
            </button>

        </div>
    </div>
    <x-modal-stock-buy />
    <x-modal-stock-sell />

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
        <thead class="text-xs  text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="py-3 px-6">
                {{__('Symbol')}}
            </th>
            <th scope="col" class="py-3 px-6">
                {{__('Anzahl')}}
            </th>
            <th scope="col" class="py-3 px-6">
                {{__('Einzelreis')}}
            </th>
            <th scope="col" class="py-3 px-6">
                {{__('Gesamtpreis')}}
            </th>
            <th scope="col" class="py-3 px-6">
                {{__('am')}}
            </th>

            <th scope="col" class="py-3 px-6">
                {{__('Transaktion')}}
            </th>

        </tr>
        </thead>
        <tbody>
        <template x-for="stock in stocks" :key="stock.id">
            <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600"
                :class="{ 'bg-red-200' : stock.type == 'sell' , 'bg-gray-200' : stock.type == 'buy'}"
            >
                <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="stock.symbol"></th>
                <td class="py-4 px-6" x-text="stock.quantity"></td>
                <td class="py-4 px-6" x-money.de-DE.EUR="stock.price"></td>
                <td class="py-4 px-6" x-money.de-DE.EUR="stock.price * stock.quantity"></td>
                <td class="py-4 px-6" x-text="stock.transaction_at"></td>
                <td class="py-4 px-6" x-text="stock.type"></td>

            </tr>
        </template>
        </tbody>
    </table>
</div>
<script>
    function formatMoney(value) {
        return value.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })
    }
</script>
