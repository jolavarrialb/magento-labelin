require([], function () {
    'use strict';

    let declineButtons = document.querySelectorAll('.acceptance-form-decline'),
        commentFieldsets = document.querySelectorAll('.fieldset-field-comment');

    [].forEach.call(commentFieldsets, function (fieldset) {
        fieldset.style.display = 'none';
    });

    [].forEach.call(declineButtons, function (button) {
        button.addEventListener('click', function () {
            let commentBlock = document.querySelector('#field-comment-' + this.dataset.orderItemId);

            if(commentBlock.style.display === 'none') {
                commentBlock.style.display = 'block';
            } else {
                commentBlock.style.display = 'none';
            }
        });
    })
});
