<div x-data="{ shares: [] }" x-init="shares = await (await fetch('/instock/shares')).json()">
<!-- Main modal -->
<div id="sell-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-1/2 h-1/2 max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{__('Aktie verringern')}}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="sell-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">{{__('schließen')}}</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" action="{{ route('transaction.reduce') }}" method="POST" >
                @csrf
                <input type="hidden" name="type" value="sell">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="symbol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Symbol')}}</label>
                        <select id="symbol" name="symbol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <template x-for="share in shares" :key="share.symbol">
                                <option x-value="share.symbol" x-text="share.symbol"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Preis')}}</label>
                        <input type="text" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="152" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('verkaufte Anzahl')}}</label>
                        <input type="text" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="152" required="">
                    </div>

                    <div class="col-span-2">
                        <label for="transaction_at" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Verkaufdatum')}}</label>
                        <input  name="transaction_at" id="datepicker-autohide transaction_at" datepicker datepicker-autohide datepicker-format="dd.mm.yyyy" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500" placeholder="Select date">
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">

                    <i class="fa-solid fa-minus mr-3"></i>{{__('Aktien verringern')}}
                </button>
            </form>
        </div>
    </div>
</div>
