define([
    'prototype',
    'jquery'
], function () {
    window.PackagingRates = Class.create();

    PackagingRates.prototype = {
        initialize: function (fromAddress, toAddress) {
            this.packagingWindow = document.querySelector('#packaging_window');
            this.formData = {};
            this.fromAddress = fromAddress;
            this.toAddress = toAddress;
            this.lbsToOz = 16;
        },

        validate: function (section) {
            let dimensionElements = this.packagingWindow
                .querySelector('#' + section.id)
                .select(
                    'input[name=container_weight],input[name=container_length],input[name=container_width],input[name=container_height],input[name=container_girth]:not("._disabled")'
                );

            return dimensionElements.collect(function (element) {
                return this.validateElement($(element).addClassName('required-entry'));
            }, this).all();
        },

        validateElement: function (element) {
            return !!(Validation.isVisible(element) && Validation.validate($(element)));
        },

        requestRates: function (object) {
            let section = object.closest('section');

            if (!this.validate(section)) {
                return this;
            }

            this.prepareFormData(section);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", '/rest/V1/pitney-bowes-rest-api/rates', true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.send(JSON.stringify({
                "fromAddress": this.fromAddress,
                "toAddress": this.toAddress,
                "parcel": this.formData
            }));

            xhr.onreadystatechange = function () {
                if (this.status === 200) {
                    let data = JSON.parse(this.responseText);

                    if (data) {
                        let select = section.querySelector('select[name=package_service]');
                        select.innerHTML = '';

                        data.forEach(function (element) {
                            let option = document.createElement('option');

                            option.appendChild(document.createTextNode(element.service + ' - $' + element.total_carrier_charge));
                            option.value = element.service_id;

                            select.appendChild(option)
                        });
                    }
                }
            };
        },

        prepareFormData: function (section) {
            let sectionElements = this.packagingWindow.querySelector('#' + section.id);

            this.formData = {
                weight: this.calculateWeight(sectionElements),
                weight_unit_of_measurement: 'OZ',
                length: sectionElements.querySelector('input[name=container_length]').value,
                width: sectionElements.querySelector('input[name=container_width]').value,
                height: sectionElements.querySelector('input[name=container_height]').value,
                dimensions_unit_of_measurement: sectionElements.querySelector('select[name=container_dimension_units]').value,
                package_container: sectionElements.querySelector('select[name=package_container]').value,
            };
        },

        calculateWeight: function (sectionElements) {
            let lbs = sectionElements.querySelector('input[name=container_weight_lb]').value ?? '0',
                oz = sectionElements.querySelector('input[name=container_weight_oz]').value ?? '0';

            return (parseInt(lbs, 10) * this.lbsToOz) + parseInt(oz, 10);
        },
    }
});
