define(['jquery', 'domReady!'], function ($) {
    'use strict';

    function init() {
        let $tierPriceBlock = $("div[data-role='tier-price-block']")

        $tierPriceBlock.on('click', 'label.ac-label', function () {
            toggleCheckedLabel(this);
        });

        $tierPriceBlock.on('click', 'input.radiobutton', function () {
            updateOrderPanel(this.value);
            updateLocalStorageData(this.value);
        });

    }

    function toggleCheckedLabel(label) {
        let $checkBoxElement = $(label).parent('div').find('input.ac-input').first();
        let checked = $checkBoxElement.is(':checked');

        if (checked) {
            $checkBoxElement.prop("checked", false);
        }

        if (!checked) {
            $checkBoxElement.prop("checked", true);
        }
    }

    function updateOrderPanel(qtyValue) {
        /**Handler for update OrderPanel**/
    }

    function updateLocalStorageData(selectedQty) {
        let currentStepItemName = 'data-step-' + localStorage.getItem('sticker_current_step');
        localStorage.setItem(currentStepItemName, selectedQty);
    }

    return function () {
        init();
    }
});
