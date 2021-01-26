define(['jquery'],
    function ($) {
        "use strict";
        return function contactUs(config, element) {
            $(document).ready(
                function ($) {
                    $('form').submit(function () {
                        $(this).find(':submit').attr('disabled', true);
                    });
                    $("form").bind("invalid-form.validate", function () {
                        $(this).find(':submit').prop('disabled', false);
                    });
                }
            );
        }
    }
);
