define(['jquery', 'domReady!'], function ($) {
    'use strict';

    function init() {
        let $tierPriceBlock = $("div[data-role='tier-price-block']");

        $tierPriceBlock.on('click', 'label.ac-label', function () {
            toggleCheckedLabel(this);
        });

        $tierPriceBlock.on('click', 'input.radiobutton', function () {
            updateLocalStorageData(this.value);
            selectOptionYourOrderStepQty(this.value);
            updateNextStepBtn(this);
        });
    }

    function toggleCheckedLabel(label) {
        let $checkBoxElement = $(label).parent('div').find('input.ac-input').first();
        let checked = $checkBoxElement.is(':checked');

        if (checked) {
            $checkBoxElement.prop("checked", false);
        }

        if (!checked) {
            $('input.ac-input').not($checkBoxElement).each(function () {
                $(this).prop('checked', false);
            });

            $checkBoxElement.prop("checked", true);
        }
    }

    function updateLocalStorageData(selectedQty) {
        let currentStepItemName = 'data-step-' + localStorage.getItem('sticker_current_step');
        localStorage.setItem(currentStepItemName, selectedQty);
    }

    function updateNextStepBtn(element) {
        let nextStepBtn = document.getElementById('sticker-next-step');

        nextStepBtn.disabled = !element.checked;
    }

    return function () {
        init();
    }
});
