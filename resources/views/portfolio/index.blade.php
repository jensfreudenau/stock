<x-layout>
    <x-header>
        <x-slot:title>{{__('Portfolio')}}</x-slot:title>
    </x-header>
    @if($active)
        @php $switchActive = 0; @endphp
    @else
        @php $switchActive = 1; @endphp
    @endif
    <section class="flex bg-white py-5 antialiased dark:bg-gray-900 md:py-5">
        <button data-modal-target="add-portfolio-modal" data-modal-toggle="add-portfolio-modal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            {{__('Portfolio hinzufügen')}}
        </button>
        <a href="/portfolio/index/{{$switchActive}}"
           class="flex items-center ml-5 p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-archive"></i>
            <span class="flex-1 ml-3 whitespace-nowrap">
                @if($switchActive)
                    {{__('Übersicht')}}
                @else
                    {{__('Archiv')}}
                @endif</span>
        </a>
    </section>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
        {{__('Portfolio')}}
        @if($active)
            {{__('Übersicht')}}
        @else
            {{__('Archiv')}}
        @endif
    </h2>
    <div class="mt-0 pt-0 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
        <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
            @if($active)
                <div x-data="{ portfolios: [] }" x-init="portfolios = await (await fetch('/portfolio/active_portfolios')).json()">
            @else
                <div x-data="{ portfolios: [] }" x-init="portfolios = await (await fetch('/portfolio/deactive_portfolios')).json()">
            @endif
                <template x-for="portfolio in portfolios" :key="portfolio.symbol">
                    <section class="mt-0 pt-0 flex flex-col md:flex-row gap-8 bg-white py-1 antialiased dark:bg-gray-900 md:py-1">
                        <div class="rounded-lg mb-6 border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                            <div class="space-y-2 md:flex  md:gap-2 md:space-y-0 ">

                                <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 ">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white" x-text="portfolio.symbol"></h5>
                                    <a :href="`/portfolio/show/${portfolio.symbol}`" class="text-blue-500 underline">Details</a>
                                    <p class="font-normal text-gray-700 dark:text-gray-400"
                                       x-text="portfolio.name"></p>
                                    <p class="font-normal text-gray-700 dark:text-gray-400"></p>
                                    <a href="#"
                                       class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                                       x-text="portfolio.description"></a>
                                    <form class="pb-3" action="{{ route('portfolio.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="symbol" value="" :value="portfolio.symbol">
                                        <button type="submit"
                                                class="block text-white bg-orange-500 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                                            {{__('update Firma')}}
                                        </button>
                                    </form>
                                </div>
                                <div class=" gap-4 pr-4">
                                    <div
                                        class=" font-bold tracking-tight text-gray-900 dark:text-white p-2">{{__('Sektor')}}
                                        <div class="font-normal text-gray-700 dark:text-gray-400"
                                             x-text="portfolio.sector"></div>
                                    </div>
                                    <div
                                        class=" font-bold tracking-tight text-gray-900 dark:text-white p-2">{{__('Country')}}
                                        <div class="font-normal text-gray-700 dark:text-gray-400"
                                             x-text="portfolio.country"></div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <form class="pb-3" action="{{ route('portfolio.deactivate') }}"
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="symbol" value="" :value="portfolio.symbol">
                                            <input type="hidden" name="active" value="" :value="portfolio.active">
                                            <template x-if="portfolio.active">
                                                <button type="submit" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                                    <i class="fa-solid fa-x pr-3" style="color: #e01024;"></i>
                                                    {{__('deaktivieren')}}
                                                </button>
                                            </template>
                                            <template x-if="!portfolio.active">
                                                <button type="submit" class="inline-flex items-center text-sm font-medium text-green-600 hover:underline dark:text-green-500">
                                                    <i class="fa-solid fa-x pr-3" style="color: #1bde36;"></i>
                                                    {{__('aktivieren')}}
                                                </button>
                                            </template>
                                        </form>
                                    </div>
                                    <div class="md:w-32 lg:w-96 md:48">
                                        <x-chart-bar symbol="${portfolio.symbol}" x-model="portfolio.symbol"> </x-chart-bar>
                                    </div>
                                </div>
                            </div>
                            <x-chart-line symbol="${portfolio.symbol}" x-model="portfolio.symbol"> </x-chart-line>

                        </div>
                    </section>
                </template>
            </div>
        </div>
    </div>
    <x-modal-portfolio-add/>
</x-layout>
