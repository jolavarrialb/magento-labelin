define([
    'jquery',
    'uiComponent',
    'ko',
    'libChart'
], function ($, Component, ko) {
    'use strict';

    return function (config) {
        $('#order-pie-chart-link').on('click', function () {
            let ordersPieChart = $('#orders-pie-chart');

            if (ordersPieChart.is(':visible')) {
                ordersPieChart.hide();
            } else {
                ordersPieChart.show();
            }
        });

        ko.bindingHandlers.chart = {
            init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
                let chartBinding = allBindingsAccessor().chart,
                    activeChart,
                    chartData;

                let createChart = function () {
                    let chartType = ko.unwrap(chartBinding.type),
                        data = ko.toJS(chartBinding.data),
                        options = ko.toJS(chartBinding.options);

                    chartData = {
                        type: chartType,
                        data: data,
                        options: options
                    };

                    element.width = config.width;
                    element.height = config.height;

                    activeChart = new Chart(element, chartData);
                };

                createChart();
            }
        };

        return Component.extend({
            defaults: {
                template: 'Labelin_Sales/chart'
            },

            initialize: function () {
                this._super();
                this.OrdersChart = {
                    labels: config.labels,
                    datasets: [{
                        data: Object.values(config.dataValues),
                        backgroundColor: [
                            '#32ce41',
                            '#008000',
                            '#e7900c',
                            '#ffce56',
                            '#f60b3d',
                            '#36a2eb',
                            '#36a2eb',
                        ],
                    }]
                };
            }
        });
    }
});
