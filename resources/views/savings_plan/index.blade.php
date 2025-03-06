<x-layout>
    <x-header>
        <x-slot:title>{{__('Sparplan')}}</x-slot:title>
    </x-header>

    <section class="flex bg-white py-5 antialiased dark:bg-gray-900 md:py-5">
        <button data-modal-target="add-savingsplan-modal" data-modal-toggle="add-savingsplan-modal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            {{__('Sparplan anlegen')}}
        </button>
    </section>
    <div x-data="{ showModal: false, selectedItem: {} }">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs  text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">
                    {{__('Portfolio')}}
                </th>
                <th scope="col" class="py-3 px-6">
                    {{__('Wert')}} €
                </th>
                <th scope="col" class="py-3 px-6">
                    {{__('Order am:')}}
                </th>

                <th scope="col" class="py-3 px-6">
                    {{__('Edit')}}
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($savingsPlans as $savingsPlan)
                <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$savingsPlan->portfolio->name}}</th>
                    <td class="py-4 px-6">{{$savingsPlan->quantity}}</td>
                    <td class="py-4 px-6">{{$savingsPlan->buy_at}}</td>
                    <td class="py-4 px-6">
                        <button data-modal-target="modal-savingsplan-edit" data-modal-toggle="modal-savingsplan-edit"
                                @click="selectedItem = {
                                 id: '{{ $savingsPlan->id }}',
                                 quantity: '{{ $savingsPlan->quantity }}',
                                 buy_at: '{{ $savingsPlan->buy_at }}',
                                 portfolio_name: '{{ $savingsPlan->portfolio->name }}'
                                 };
                                 showModal = true"
                                >
                            <i class="fas fa-edit"></i>
                        </button>
                        &nbsp;
{{--                        <form method="POST" action="{{ route('savingsplan.destroy', $savingsplan) }}"--}}
{{--                              onsubmit="return confirm('Are you sure:');">--}}
{{--                            @method('DELETE')--}}
{{--                            @csrf--}}
{{--                            <button type="submit" class="">--}}
{{--                                <i class="fa-solid fa-trash-can"></i>--}}
{{--                            </button>--}}
{{--                        </form>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <x-modal-savingsplan-edit />
    </div>

    <x-modal-savingsplan-add :portfolios="$portfolios" />
{{--    <x-modal-savingsplan-add portfolios="{!! $portfolios !!}" />--}}
</x-layout>
