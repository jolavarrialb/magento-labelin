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

            $(document).ready(function ($) {
                $('#contact-form').submit(function (event) {
                    let $form = $('#contact-form');
                    let url = $form.attr('action');

                    event.preventDefault();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $form.serialize(),
                        cache: false,
                        success: function (data) {
                            if (data['error']) {
                                $form.find(':submit').prop('disabled', false);
                            }

                            if (!data['error']) {
                                $form.find(':submit').prop('disabled', true);
                            }

                            alert(data['response']);
                        }
                    });
                });
            });
        }
    }
);
