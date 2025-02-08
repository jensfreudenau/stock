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
                <a href="/balance/index"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="pl-1 fa-solid fa-money-bill-trend-up"></i>
                    <span class="flex-1 ml-3 whitespace-nowrap">{{__('Bilanz')}}</span>

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
                <a href="/portfolio/index"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="pl-1 fa-solid fa-industry"></i>
                    <span class="flex-1 ml-3 whitespace-nowrap">{{__('Portfolio')}}</span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg aria-hidden="true"
                         class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z">
                        </path>
                        <path
                            d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z">
                        </path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Inbox</span>
                    <span
                        class="inline-flex justify-center items-center p-3 ml-3 w-3 h-3 text-sm font-medium text-blue-600 bg-blue-200 rounded-full dark:bg-blue-900 dark:text-blue-200">3</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
