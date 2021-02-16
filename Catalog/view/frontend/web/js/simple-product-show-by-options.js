define(['jquery', 'domReady!'], function ($) {
    'use strict';

    function init() {
        let $priceBlockWrapper = $("div[data-role='priceBox']").find('span.price-wrapper');
        let $optionsBlockSelector = $('#product-options-wrapper').find('select.product-custom-option');

        $optionsBlockSelector.on('change', function () {
            $priceBlockWrapper.show();
        });
    }

    return function () {
        init();
    }
});
