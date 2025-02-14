<aside class="fixed md:static md:translate-x-0 top-0 left-0 h-full w-64 bg-white shadow-md transform transition-transform duration-300 ease-in-out z-30" :class="open ? 'translate-x-0' : '-translate-x-full'" aria-label="Sidebar">
    <div class="overflow-y-auto py-4 pl-4 pr-16 h-screen rounded dark:bg-gray-800">

        <ul class="space-y-2">
            <li>
                <a href="/"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="pl-1 fa-solid fa-hurricane"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/transaction/index"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="pl-1 fa-solid fa-money-bill-trend-up"></i>
                    <span class="flex-1 ml-3 whitespace-nowrap">{{__('Transaktionen')}}</span>

                </a>
            </li>
            <li>
                <a href="/statistic"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="pl-1 fa-solid fa-chart-line"></i>
                    <span class="flex-1 ml-3 whitespace-nowrap">{{__('Statistic')}}</span>

                </a>
            </li>
            <li>
                <a href="/portfolio/index/1"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="pl-1 fa-solid fa-industry"></i>
                    <span class="flex-1 ml-3 whitespace-nowrap">{{__('Portfolio')}}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
