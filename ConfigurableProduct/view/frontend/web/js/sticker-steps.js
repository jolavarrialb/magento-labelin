document.addEventListener('swatch-full-render', function () {
    let stepsSelectors = document.querySelectorAll('div[data-step]');

    document
        .getElementById('sticker_price')
        .setAttribute('data-step', stepsSelectors.length + 1);
    document
        .getElementById('sticker_artwork')
        .setAttribute('data-step', stepsSelectors.length + 2);
});

document.addEventListener('swatch-select-option', function () {
    let nextStep = document.getElementById('sticker-next-step');

    nextStep.disabled = false;
});

document.addEventListener('swatch-unselect-option', function () {
    let nextStep = document.getElementById('sticker-next-step');

    nextStep.disabled = true;
});

document.addEventListener('DOMContentLoaded', function () {
    let backStep = document.getElementById('sticker-back-step'),
        nextStep = document.getElementById('sticker-next-step'),
        submitStep = document.getElementById('product-addtocart-button');

    if (isFirstStep()) {
        hideBtn(backStep);
    }

    backStep.addEventListener('click', function () {
        let currentStep = parseInt(localStorage.getItem('sticker_current_step'));

        proceedStep(currentStep - 1);

        nextStep.disabled = false;
        if (document.querySelector('div[data-step="' + (currentStep - 1) + '"] .swatch-option')) {
            nextStep.disabled = !document.querySelector('div[data-step="' + (currentStep - 1) + '"] .swatch-option.selected');
        }

        if (isFirstStep()) {
            hideBtn(this);
        }

        if (document.querySelectorAll('div[data-step]').length > parseInt(localStorage.getItem('sticker_current_step'))) {
            showBtn(nextStep);
            hideBtn(submitStep);
        }
    });

    nextStep.addEventListener('click', function () {
        let currentStep = parseInt(localStorage.getItem('sticker_current_step'));

        proceedStep(currentStep + 1);

        this.disabled = false;
        if (document.querySelector('div[data-step="' + (currentStep + 1) + '"] .swatch-option')) {
            this.disabled = !document.querySelector('div[data-step="' + (currentStep + 1) + '"] .swatch-option.selected');
        }

        if (isLastStep()) {
            hideBtn(this);
            showBtn(submitStep);
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
        element.disabled = true;
    }

    function showBtn(element) {
        element.style.display = 'block';
        element.disabled = false;
    }
});


