define([
    'mage/url',
    'domReady!'
], function () {
    'use strict';

    return function (config) {
        let fileInput = document.querySelector('input.product-custom-option[type="file"]');

        fileInput.addEventListener('change', function () {
            renderPreview(this);
            document.querySelector('.input-artwork-choose').value = this.files[0].name;
        });

        function renderPreview(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader(),
                    inputFile = input.files[0];

                reader.onload = function (e) {
                    let imagePreview = document.getElementById('preview-image');

                    imagePreview.setAttribute('src', e.target.result);
                    document.querySelector('.preview-text').style.display = 'none';

                    if (inputFile.type === 'image/x-eps') {
                        imagePreview.setAttribute('src', config.imageEps);
                    }

                    if (inputFile.type === 'application/pdf') {
                        imagePreview.setAttribute('src', config.imagePdf);
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    }
});
