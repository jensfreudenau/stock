 <x-card>
    <x-slot:title>{{$year}}</x-slot:title>
    <div class="shadow-md sm:rounded-lg" x-data="{ stocks: [] }" x-init="stocks = await (await fetch('/statistic/getProfitsByYear/{{$year}}')).json()">
        <table class="text-sm   text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs  text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6 ">
                    {{__('Symbol')}}
                </th>
                <th scope="col" class="py-3 px-6">
                    {{__('Profit')}}
                </th>

                <th scope="col" class="py-3 px-6">
                    {{__('Verkauft am')}}
                </th>
            </tr>
            </thead>
            <tbody>
            <template x-for="stock in stocks">
                <template x-if="stock.transaction_at">
                    <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="stock.symbol"></th>
                        <td class="py-4 px-6" x-money.de-DE.EUR="stock.profit"></td>
                        <td class="py-4 px-6" x-text="formatDateDisplay(stock.transaction_at)"></td>
                    </tr>
                </template>
            </template>
            </tbody>
            <tfoot>
                <tr class="bg-gray-200  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="py-4 px-6 text-m font-medium text-gray-900 whitespace-nowrap dark:text-white">insg.</td>
                    <td class="py-4 px-6 font-bold underline decoration-slice" x-money.de-DE.EUR="stocks.sum_profit"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-card>
 <script>
     function formatDateDisplay(date) {
         return new Date(date).toLocaleDateString('de-DE');
     }
 </script>
