import './bootstrap';

import 'flowbite';
import Alpine from 'alpinejs';
import money from 'alpinejs-money';
import ApexCharts from 'apexcharts';
import Chart from 'chart.js/auto';

Alpine.plugin(money);
window.Chart = Chart;
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
Alpine.start();
