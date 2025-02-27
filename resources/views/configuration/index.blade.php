<x-layout>
    <x-header>
        <x-slot:title>{{__('Konfiguration')}}</x-slot:title>
    </x-header>
    <section class="flex bg-white py-5 antialiased dark:bg-gray-900 md:py-5">
        <button data-modal-target="add-configuration-modal" data-modal-toggle="add-configuration-modal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            {{__('Konfiguration hinzufügen')}}
        </button>
    </section>
    <div x-data="{ showModal: false, selectedItem: {} }">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs  text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">
                    {{__('Name')}}
                </th>
                <th scope="col" class="py-3 px-6">
                    {{__('Key')}}
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
            @foreach($configurations as $configuration)
                <tr class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$configuration->name}}</th>
                    <td class="py-4 px-6">{{$configuration->key}}</td>
                    <td class="py-4 px-6">{{$configuration->value}}</td>
                    <td class="py-4 px-6">
                        <button data-modal-target="modal-configuration-edit" data-modal-toggle="modal-configuration-edit"
                            @click="selectedItem = { id: '{{ $configuration->id }}', name: '{{ $configuration->name }}', key: '{{ $configuration->key }}', value: '{{ $configuration->value }}' }; showModal = true"
                            class="">
                            <i class="fas fa-edit"></i>
                        </button>
                        &nbsp;
                      <form method="POST" action="{{ route('configuration.destroy', $configuration) }}"
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
        <x-modal-configuration-edit />
    </div>
    <x-modal-configuration-add />

</x-layout>
