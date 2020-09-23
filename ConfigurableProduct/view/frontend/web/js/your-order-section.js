function processYourOrderSection() {
    let yourOrderSection = document.getElementById('your-order-section');

    [].forEach.call(document.querySelectorAll('div[data-step]'), function (element) {
        let label = element.querySelector('.swatch-attribute-label').innerHTML,
            dataStep = element.getAttribute('data-step');

        let div = document.createElement('div');
        div.className = 'value';
        div.id = 'your-order-section-step-' + dataStep;

        let divLabel = document.createElement('div');
        divLabel.className = 'value-text';
        divLabel.innerHTML = label;
        div.append(divLabel);

        if (localStorage.getItem('data-step-' + dataStep)) {
            let divValue = document.createElement('div');
            divValue.className = 'selected-value';
            divValue.innerHTML = localStorage.getItem('data-step-' + dataStep);
            div.append(divValue);
        }

        yourOrderSection.append(div);
    });
}

function selectOptionYourOrderStep() {
    let currentStep = parseInt(localStorage.getItem('sticker_current_step')),
        yourOrderStep = document.getElementById('your-order-section-step-' + currentStep),
        value = localStorage.getItem('data-step-' + currentStep);

    if (value) {
        let divValue = document.createElement('div');
        divValue.className = 'selected-value';
        divValue.innerHTML = value;
        yourOrderStep.append(divValue);
        yourOrderStep.classList.add('checked');
    }
}

function unselectOptionYourOrderStep() {
    let currentStep = parseInt(localStorage.getItem('sticker_current_step')),
        yourOrderStep = document.getElementById('your-order-section-step-' + currentStep),
        yourOrderStepValue = yourOrderStep.querySelector('div.selected-value');

    yourOrderStep.classList.remove('checked');

    if (yourOrderStepValue) {
        yourOrderStep.removeChild(yourOrderStepValue);
    }
}

function uploadFileListener() {
    let fileInput = document.querySelector('input.product-custom-option[type="file"]');

    fileInput.addEventListener('change', function () {
        unselectOptionYourOrderStep();
        localStorage.setItem('data-step-' + parseInt(localStorage.getItem('sticker_current_step')), this.files[0].name);
        selectOptionYourOrderStep();
    });
}
