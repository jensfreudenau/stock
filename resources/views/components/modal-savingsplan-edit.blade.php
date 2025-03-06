
<div id="modal-savingsplan-edit" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-1/2 h-1/2 max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{__('Sparplan bearbeiten')}}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="modal-savingsplan-edit">
                    <i class="fa-solid fa-xmark"></i>
                    <span class="sr-only">{{__('schließen')}}</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" action="{{ route('savingsplan.update') }}" method="POST" >
                @csrf
                <input type="hidden" name="id" x-model="selectedItem.id">
                <div class="grid gap-4 mb-4 grid-cols-2">

                    <div class="col-span-2">
                        <div x-text="selectedItem.portfolio_name"></div>
                    </div>
                    <div class="col-span-2">
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Wert')}}</label>
                        <input type="number" x-model="selectedItem.quantity" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{__('Wert')}} €" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="buy_at" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Tag des Monats')}}</label>
                        <input type="text" x-model="selectedItem.buy_at"  name="buy_at" id="buy_at" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{__('Kaufdatum')}}" required="">
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fa-solid fa-plus pt-1/2"></i> &nbsp;
                    {{__('Sparplan speichern')}}
                </button>
            </form>
        </div>
    </div>
</div>

