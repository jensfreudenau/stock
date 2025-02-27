<div id="add-portfolio-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed   right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-1/2 h-1/2 max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{__('neues Portfolio hinzufügen')}}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-portfolio-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">{{__('schließen')}}</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" action="{{ route('portfolio.initial') }}" method="POST" >
                @csrf
                <input type="hidden" name="active" value="1">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="symbol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Symbol')}}</label>
                        <input type="text" name="symbol" id="symbol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{__('Symbol')}}" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="isin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('ISIN')}}</label>
                        <input type="text" name="isin" id="isin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{__('ISIN')}}" required="">
                    </div>
                    <h3 class="col-span-2 font-semibold text-gray-900 dark:text-white">{{__('Akteintyp')}}</h3>
                    <div class=" flex  items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700">
                        <input id="share" type="radio" value="share" name="share_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="share" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{__('Aktie')}}</label>
                    </div>
                    <div class=" flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700">
                        <input id="etf" type="radio" value="etf" name="share_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="etf" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{__('ETF')}}</label>
                    </div>

                    <div class="col-span-2">
                        <label for="active_since" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Kaufdatum')}}</label>
                        <input  name="active_since" id="datepicker-autohide active_since" datepicker datepicker-autohide datepicker-format="dd.mm.yyyy" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{__('Kaufdatum')}}">
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-plus pt-1/2"></i> &nbsp;
                    {{__('neues Portfolio hinzufügen')}}
                </button>
            </form>
        </div>
    </div>
</div>
