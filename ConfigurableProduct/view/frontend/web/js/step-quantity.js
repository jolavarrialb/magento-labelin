function initStepQuantity() {

    let tierPriceBlock = jQuery("div[data-role='tier-price-block']"),
        sizeBlock = jQuery(".sticker_size").find(".swatch-option");

    sizeBlock.on('click', function () {
        clearYourOrderSectionQty();
    });

    tierPriceBlock.on('click', 'input.radiobutton', function () {
        updateLocalStorageData(this.value);
        selectOptionYourOrderStepQty(this.value);
        updateNextStepBtn(this);
    });
}

function updateLocalStorageData(selectedQty) {
    let currentStepItemName = 'data-step-' + localStorage.getItem('sticker_current_step');
    localStorage.setItem(currentStepItemName, selectedQty);
}

function updateNextStepBtn(element) {
    let nextStepBtn = document.getElementById('sticker-next-step');

    nextStepBtn.disabled = !element.checked;
}

function disableNextStepBtn() {
    let nextStepBtn = document.getElementById('sticker-next-step');

    nextStepBtn.disabled = true;
}

function clearYourOrderSectionQty() {
    unselectOptionYourOrderStepQty();
    disableNextStepBtn();
}
