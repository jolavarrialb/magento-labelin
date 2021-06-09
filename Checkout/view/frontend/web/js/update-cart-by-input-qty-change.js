define(['jquery', 'domReady!'], function ($) {
    'use strict';

    function init() {
        let $qtyInput = $("input[data-role='cart-item-qty']");
        let $cartActionsUpdateButtonForm = $('#form-validate');

        $qtyInput.on('input', function () {
            $cartActionsUpdateButtonForm.submit();
        });
    }

    return function () {
        init();
    }
});
