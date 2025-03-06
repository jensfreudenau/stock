<div>
    <!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
</div>
<x-layout>
    <x-header>
        <x-slot:title>{{__('Stop Loss')}}</x-slot:title>
    </x-header>
    <section class="flex bg-white py-5 antialiased dark:bg-gray-900 md:py-5">
        <button data-modal-target="add-stoploss-modal" data-modal-toggle="add-stoploss-modal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            {{__('Stop Loss hinzufügen')}}
        </button>
    </section>
    <div x-data="{ showModal: false, selectedItem: {} }">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs  text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">
                    {{__('portfolio_id')}}
                </th>
                <th scope="col" class="py-3 px-6">
                    {{__('Value')}}
                </th>

                <th scope="col" class="py-3 px-6">
                    {{__('Edit')}}
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($stopLosses as $stopLoss)
                <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$stopLoss->portfolio->name}}</th>
                    <td class="py-4 px-6">{{$stopLoss->value}}</td>
                    <td class="py-4 px-6">
                        <button data-modal-target="modal-stoploss-edit" data-modal-toggle="modal-stoploss-edit"
                                @click="selectedItem = { id: '{{ $stopLoss->id }}',  value: '{{ $stopLoss->value }}',  portfolio_id: '{{ $stopLoss->portfolio_id }}' }; showModal = true"
                                class="">
                            <i class="fas fa-edit"></i>
                        </button>
                        &nbsp;
                        <form method="POST" action="{{ route('stoploss.destroy', $stopLoss) }}"
                              onsubmit="return confirm('Are you sure:');">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <x-modal-stoploss-edit />
    </div>
    <x-modal-stoploss-add />

</x-layout>
