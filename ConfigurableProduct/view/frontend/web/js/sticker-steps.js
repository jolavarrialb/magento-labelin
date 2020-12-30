document.addEventListener('swatch-full-render', function () {
    let stepsSelectors = document.querySelectorAll('div[data-step]'),
        stickerStepsWrapper = document.getElementById('sticker-steps-wrapper');

    document
        .getElementById('sticker_price')
        .setAttribute('data-step', stepsSelectors.length + 1);
    document
        .getElementById('sticker_artwork')
        .setAttribute('data-step', stepsSelectors.length + 2);

    document.querySelectorAll('div[data-step]').forEach(function (element) {
        let stepDiv = document.createElement('div');

        stepDiv.className = 'sticker-step-info info-nonactive';
        stepDiv.setAttribute('data-step-wrapper', element.getAttribute('data-step'));
        stickerStepsWrapper.append(stepDiv);
    });

    processCurrentStepWrapper();
    processYourOrderSection()
});

document.addEventListener('swatch-select-option', function () {
    let nextStep = document.getElementById('sticker-next-step');

    nextStep.disabled = false;

    selectOptionYourOrderStep();
});

document.addEventListener('swatch-unselect-option', function () {
    let nextStep = document.getElementById('sticker-next-step');

    nextStep.disabled = true;

    unselectOptionYourOrderStep();
});

document.addEventListener('DOMContentLoaded', function () {
    let backStep = document.getElementById('sticker-back-step'),
        nextStep = document.getElementById('sticker-next-step'),
        submitStep = document.getElementById('product-addtocart-button');

    if (isFirstStep()) {
        hideBtn(backStep);
    }

    backStep.addEventListener('click', function () {
        let currentStep = parseInt(localStorage.getItem('sticker_current_step')),
            isAvailableSwatchOption = document.querySelector('div[data-step="' + (currentStep - 1) + '"] .swatch-option'),
            previousSelectedSwatchOption = document.querySelector('div[data-step="' + (currentStep - 1) + '"] .swatch-option.selected'),
            currentSelectedSwatchOption = document.querySelector('div[data-step="' + (currentStep) + '"] .swatch-option.selected'),
            fileInput = document.querySelector('input.product-custom-option[type="file"]');

        if (currentSelectedSwatchOption) {
            currentSelectedSwatchOption.click();
        }

        unselectOptionYourOrderStep();

        proceedStep(currentStep - 1);

        processCurrentStepWrapper();

        nextStep.disabled = false;
        if (isAvailableSwatchOption) {
            nextStep.disabled = !previousSelectedSwatchOption;
        }

        if (isFirstStep()) {
            hideBtn(this);
        }

        currentStep = parseInt(localStorage.getItem('sticker_current_step'));

        if (document.querySelectorAll('div[data-step]').length > currentStep) {
            showBtn(nextStep);
            hideBtn(submitStep);
        }

        if (fileInput &&
            parseInt(fileInput.closest('#sticker_artwork').getAttribute('data-step')) === currentStep + 1
        ) {
            fileInput.value = '';
            document.querySelector('.input-artwork-choose').value = '';
            document.querySelector('.preview-text').style.display = 'block';
            document.getElementById('preview-image').removeAttribute('src');
        }
    });

    nextStep.addEventListener('click', function () {
        let currentStep = parseInt(localStorage.getItem('sticker_current_step')),
            isAvailableSwatchOption = document.querySelector('div[data-step="' + (currentStep + 1) + '"] .swatch-option'),
            selectedSwatchOption = document.querySelector('div[data-step="' + (currentStep + 1) + '"] .swatch-option.selected'),
            qtyInput = document.querySelector('input[name="qty"]'),
            qtyInputCheckedList = document.querySelectorAll('input[name="qty"]:checked');

        proceedStep(currentStep + 1);

        processCurrentStepWrapper();

        this.disabled = false;
        if (isAvailableSwatchOption) {
            this.disabled = !selectedSwatchOption;
        }

        if (isLastStep()) {
            hideBtn(this);
            showBtn(submitStep);
        }

        currentStep = parseInt(localStorage.getItem('sticker_current_step'));

        if (currentStep > 1) {
            showBtn(backStep);
        }

        if (qtyInput &&
            parseInt(qtyInput.closest('#sticker_price').getAttribute('data-step')) === currentStep
        ) {
            document.querySelector('input#ac-1').setAttribute('checked', 'checked');

            this.disabled = !qtyInputCheckedList.length;

            if (qtyInputCheckedList.length) {
                localStorage.setItem('data-step-' + localStorage.getItem('sticker_current_step'), qtyInputCheckedList[0].value);
                selectOptionYourOrderStep();
            }
        }
    });

    uploadFileListener();
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

function processCurrentStepWrapper() {
    let currentStep = parseInt(localStorage.getItem('sticker_current_step')),
        currentStepWrapper = document.querySelector('.sticker-step-info[data-step-wrapper="' + currentStep + '"]'),
        nextStepWrapper = document.querySelector('.sticker-step-info[data-step-wrapper="' + (currentStep + 1) + '"]'),
        swatchLabel = document.querySelector('div[data-step="' + currentStep + '"] .swatch-attribute-label'),
        stepWrapperLabel = '<span class="step-qty-info">Step ' + currentStep + ':</span> ' + swatchLabel.innerHTML;

    if (!isLastStep()) {
        nextStepWrapper.classList.remove('info-active');
        nextStepWrapper.classList.add('info-nonactive');
    }

    currentStepWrapper.classList.remove('info-nonactive');
    currentStepWrapper.classList.add('info-active');

    document.getElementById('sticker-steps-wrapper-info').innerHTML = stepWrapperLabel;
}
