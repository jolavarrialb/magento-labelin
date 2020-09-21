document.addEventListener('swatch-full-render', function () {
    let stepsSelectors = document.querySelectorAll('div[data-step]');

    document
        .querySelector('#sticker_price')
        .setAttribute('data-step', stepsSelectors.length + 1);
    document
        .querySelector('#sticker_artwork')
        .setAttribute('data-step', stepsSelectors.length + 2);
});

document.addEventListener('DOMContentLoaded', function () {
    let backStep = document.querySelector('#sticker-back-step'),
        nextStep = document.querySelector('#sticker-next-step');

    if (isFirstStep()) {
        hideBtn(backStep);
    }

    backStep.addEventListener('click', function () {
        proceedStep(parseInt(localStorage.getItem('sticker_current_step')) - 1);

        if (isFirstStep()) {
            hideBtn(this);
        }

        if (document.querySelectorAll('div[data-step]').length > parseInt(localStorage.getItem('sticker_current_step'))) {
            showBtn(nextStep);
        }
    });

    nextStep.addEventListener('click', function (e) {

        proceedStep(parseInt(localStorage.getItem('sticker_current_step')) + 1);

        if (isLastStep()) {
            hideBtn(this);
        }

        if (parseInt(localStorage.getItem('sticker_current_step')) > 1) {
            showBtn(backStep);
        }
    });

    function proceedStep(stepId) {
        let currentStep = parseInt(localStorage.getItem('sticker_current_step'));

        document.querySelector('div[data-step="' + currentStep + '"]').style.display = 'none';
        document.querySelector('div[data-step="' + (stepId) + '"]').style.display = 'block';

        localStorage.removeItem('sticker_current_step');
        localStorage.setItem('sticker_current_step', stepId);
    }

    function isFirstStep() {
        return parseInt(localStorage.getItem('sticker_current_step')) === 1;
    }

    function isLastStep() {
        return document.querySelectorAll('div[data-step]').length === parseInt(localStorage.getItem('sticker_current_step'));
    }

    function hideBtn(element) {
        element.style.display = 'none';
    }

    function showBtn(element) {
        element.style.display = 'block';
    }
});


