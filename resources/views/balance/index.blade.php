<x-layout>

    <x-header>
        <x-slot:title>{{__('Bilanz')}}</x-slot:title>

    </x-header>


    <section class="my-12 mt-8 flex flex-col md:flex-row gap-8">
        <x-table />
    </section>

</x-layout>
