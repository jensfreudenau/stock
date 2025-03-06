import './bootstrap';

import 'flowbite';
import Alpine from 'alpinejs';
import money from 'alpinejs-money';
import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';
import {de} from 'date-fns/locale';
Alpine.plugin(money);
window.Chart = Chart;
window.Alpine = Alpine;
Alpine.start();

function initBarChart($id, $symbol) {

    fetch(`/portfolio/analytics/` + $symbol)
        .then(res => res.json())
        .then(data => {
            barChart(data, $id)
        });
}

function barChart(data, $id) {

    console.log(data['company']['analyst_rating_strong_buy'] );
    let ctc = document.getElementById($id);

    let datas = {
        labels: ['strong buy', 'buy', 'hold', 'sell', 'strong sell'],
        datasets: [{
            backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
            data: [
                data['company']['analyst_rating_strong_buy'],
                data['company']['analyst_rating_buy'],
                data['company']['analyst_rating_hold'],
                data['company']['analyst_rating_sell'],
                data['company']['analyst_rating_strong_sell']
            ]
        }]
    };
    new Chart(ctc, {
        type: 'bar',
        data: datas,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function initChartLine($id, $symbol) {
    fetch(`/statistic/chart/` + $symbol)
        .then(res => res.json())
        .then(data => {
            chartLine(data, $id)
        });
}

function chartLine(data, $id) {
    const ctx = document.getElementById($id);
    new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                data: data,
                borderWidth: 1
            }]
        },
        options: {

            scales: {

                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'PPP', // z. B. "1. März 2024"
                    },
                    adapters: {
                        date: {
                            locale: de // Deutsche Lokalisierung
                        }
                    }
                },
                y: {
                    ticks: {
                        callback: function(val) {
                            return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(val)
                        },
                    },
                },
            },
            responsive: true,
            elements: {
                point: {
                    radius: 0
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

window.initChartLine = initChartLine;
window.initBarChart = initBarChart;

