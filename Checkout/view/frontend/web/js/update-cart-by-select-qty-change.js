define(['jquery', 'domReady!'], function ($) {
    'use strict';

    function init() {
        let $qtySelect = $("select[data-role='cart-item-qty']");
        let $cartActionsUpdateButtonForm = $('#form-validate');

        $qtySelect.on('change', function () {
            $cartActionsUpdateButtonForm.submit();
        });
    }

    return function () {
        init();
    }
});
