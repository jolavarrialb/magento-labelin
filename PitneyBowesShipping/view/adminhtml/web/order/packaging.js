define([
    'jquery',
    'Magento_Shipping/order/packaging'
], function (jquery, packaging) {
    'use strict';

    if (window.packaging !== "undefined") {
        window.Packaging.addMethods({
            initialize: function (params) {
                this.packageIncrement = 0;
                this.packages = [];
                this.packagesTypes = params.packagesTypes ? params.packagesTypes : false;
                this.itemsAll = [];
                this.createLabelUrl = params.createLabelUrl ? params.createLabelUrl : null;
                this.itemsGridUrl = params.itemsGridUrl ? params.itemsGridUrl : null;
                this.errorQtyOverLimit = params.errorQtyOverLimit;
                this.titleDisabledSaveBtn = params.titleDisabledSaveBtn;
                this.window = $('packaging_window');
                this.messages = this.window.select('.message-warning')[0];
                this.packagesContent = $('packages_content');
                this.template = $('package_template');
                this.paramsCreateLabelRequest = {};
                this.validationErrorMsg = params.validationErrorMsg;

                this.defaultItemsQty = params.shipmentItemsQty ? params.shipmentItemsQty : null;
                this.defaultItemsPrice = params.shipmentItemsPrice ? params.shipmentItemsPrice : null;
                this.defaultItemsName = params.shipmentItemsName ? params.shipmentItemsName : null;
                this.defaultItemsWeight = params.shipmentItemsWeight ? params.shipmentItemsWeight : null;
                this.defaultItemsProductId = params.shipmentItemsProductId ? params.shipmentItemsProductId : null;
                this.defaultItemsOrderItemId = params.shipmentItemsOrderItemId ? params.shipmentItemsOrderItemId : null;

                this.shippingInformation = params.shippingInformation ? params.shippingInformation : null;
                this.thisPage = params.thisPage ? params.thisPage : null;
                this.customizableContainers = params.customizable ? params.customizable : [];

                this.eps = 0.000001;
            },
            sendCreateLabelRequest: function () {
                var self = this;

                this.packagesContent.childElements().each(function (pack) {
                    let pkgType = pack.select('select[name="package_container"]')[0].value;
                    let dimensionRequired = self.packagesTypes[pkgType].dimensionRules.required;

                    if (dimensionRequired && !self.validate(pack)) {
                        self.messages.show().update(self.validationErrorMsg);

                        return;
                    }
                });

                this.messages.hide().update();

                if (this.createLabelUrl) {
                    var weight, length, width, height, service = null;
                    var packagesParams = [];

                    this.packagesContent.childElements().each(function (pack) {
                        var packageId = this.getPackageId(pack);

                        weight = parseFloat(this.calculateWeight(pack));
                        length = parseFloat(pack.select('input[name="container_length"]')[0].value);
                        width = parseFloat(pack.select('input[name="container_width"]')[0].value);
                        height = parseFloat(pack.select('input[name="container_height"]')[0].value);
                        service = pack.select('select[name="package_service"]')[0].value;

                        packagesParams[packageId] = {
                            container: pack.select('select[name="package_container"]')[0].value,
                            customs_value: parseFloat(pack.select('input[name="package_customs_value"]')[0].value, 10),
                            weight: isNaN(weight) ? '' : weight,
                            length: isNaN(length) ? '' : length,
                            width: isNaN(width) ? '' : width,
                            height: isNaN(height) ? '' : height,
                            service: service === null ? '' : service,
                            weight_units: 'OZ',
                            dimension_units: pack.select('select[name="container_dimension_units"]')[0].value,

                            fromAddress: JSON.stringify(self.fromAddress),
                            toAddress: JSON.stringify(self.toAddress),
                        };

                        if (isNaN(packagesParams[packageId]['customs_value'])) {
                            packagesParams[packageId]['customs_value'] = 0;
                        }

                        if ('undefined' != typeof pack.select('select[name="package_size"]')[0]) {
                            if ('' != pack.select('select[name="package_size"]')[0].value) {
                                packagesParams[packageId]['size'] = pack.select('select[name="package_size"]')[0].value;
                            }
                        }

                        if ('undefined' != typeof pack.select('input[name="container_girth"]')[0]) {
                            if ('' != pack.select('input[name="container_girth"]')[0].value) {
                                packagesParams[packageId]['girth'] = pack.select('input[name="container_girth"]')[0].value;
                                packagesParams[packageId]['girth_dimension_units'] = pack.select('select[name="container_girth_dimension_units"]')[0].value;
                            }
                        }

                        if ('undefined' != typeof pack.select('select[name="content_type"]')[0] && 'undefined' != typeof pack.select('input[name="content_type_other"]')[0]) {
                            packagesParams[packageId]['content_type'] = pack.select('select[name="content_type"]')[0].value;
                            packagesParams[packageId]['content_type_other'] = pack.select('input[name="content_type_other"]')[0].value;
                        } else {
                            packagesParams[packageId]['content_type'] = '';
                            packagesParams[packageId]['content_type_other'] = '';
                        }
                        var deliveryConfirmation = pack.select('select[name="delivery_confirmation_types"]');

                        if (deliveryConfirmation.length) {
                            packagesParams[packageId]['delivery_confirmation'] = deliveryConfirmation[0].value;
                        }
                    }.bind(this));

                    for (var packageId in this.packages) {
                        if (!isNaN(packageId)) {
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[container]'] = packagesParams[packageId]['container'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[weight]'] = packagesParams[packageId]['weight'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[customs_value]'] = packagesParams[packageId]['customs_value'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[length]'] = packagesParams[packageId]['length'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[width]'] = packagesParams[packageId]['width'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[height]'] = packagesParams[packageId]['height'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[weight_units]'] = packagesParams[packageId]['weight_units'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[dimension_units]'] = packagesParams[packageId]['dimension_units'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[content_type]'] = packagesParams[packageId]['content_type'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[content_type_other]'] = packagesParams[packageId]['content_type_other'];

                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[service]'] = packagesParams[packageId]['service'];

                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[fromAddress]'] = packagesParams[packageId]['fromAddress'];
                            this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[toAddress]'] = packagesParams[packageId]['toAddress'];

                            if ('undefined' != typeof packagesParams[packageId]['size']) {
                                this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[size]'] = packagesParams[packageId]['size'];
                            }

                            if ('undefined' != typeof packagesParams[packageId]['girth']) {
                                this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[girth]'] = packagesParams[packageId]['girth'];
                                this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[girth_dimension_units]'] = packagesParams[packageId]['girth_dimension_units'];
                            }

                            if ('undefined' != typeof packagesParams[packageId]['delivery_confirmation']) {
                                this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[params]' + '[delivery_confirmation]'] = packagesParams[packageId]['delivery_confirmation'];
                            }

                            for (var packedItemId in this.packages[packageId]['items']) {
                                if (!isNaN(packedItemId)) {
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][qty]'] = this.packages[packageId]['items'][packedItemId]['qty'];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][customs_value]'] = this.packages[packageId]['items'][packedItemId]['customs_value'];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][price]'] = self.defaultItemsPrice[packedItemId];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][name]'] = self.defaultItemsName[packedItemId];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][weight]'] = self.defaultItemsWeight[packedItemId];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][product_id]'] = self.defaultItemsProductId[packedItemId];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][order_item_id]'] = self.defaultItemsOrderItemId[packedItemId];
                                    this.paramsCreateLabelRequest['packages[' + packageId + ']' + '[items]' + '[' + packedItemId + '][service]'] = packagesParams[packageId]['service'];
                                }
                            }
                        }
                    }

                    new Ajax.Request(this.createLabelUrl, {
                        parameters: this.paramsCreateLabelRequest,
                        onSuccess: function (transport) {
                            var response = transport.responseText;

                            if (response.isJSON()) {
                                response = response.evalJSON();

                                if (response.error) {
                                    this.messages.show().innerHTML = response.message;
                                } else if (response.ok && Object.isFunction(this.labelCreatedCallback)) {
                                    this.labelCreatedCallback(response);
                                }
                            }
                        }.bind(this)
                    });

                    if (this.paramsCreateLabelRequest['code'] &&
                        this.paramsCreateLabelRequest['carrier_title'] &&
                        this.paramsCreateLabelRequest['method_title'] &&
                        this.paramsCreateLabelRequest['price']
                    ) {
                        var a = this.paramsCreateLabelRequest['code'];
                        var b = this.paramsCreateLabelRequest['carrier_title'];
                        var c = this.paramsCreateLabelRequest['method_title'];
                        var d = this.paramsCreateLabelRequest['price'];

                        this.paramsCreateLabelRequest = {};
                        this.paramsCreateLabelRequest['code'] = a;
                        this.paramsCreateLabelRequest['carrier_title'] = b;
                        this.paramsCreateLabelRequest['method_title'] = c;
                        this.paramsCreateLabelRequest['price'] = d;
                    } else {
                        this.paramsCreateLabelRequest = {};
                    }
                }
            },
            updateDimensions: function (object, jquery) {
                const optionCustom = 'PKG';

                let $object = jquery(object),
                    packageBlock = $object.parents('[id^="package_block"]').first(),
                    dimensionIsRequired = false,
                    packageLength = packageBlock.find('[name="container_length"]'),
                    packageWidth = packageBlock.find('[name="container_width"]'),
                    packageHeight = packageBlock.find('[name="container_height"]'),
                    dimensionUnitsIn = packageBlock.find('[name="container_dimension_units"]');

                if (this.packagesTypes.hasOwnProperty(object.value)) {
                    dimensionIsRequired = this.packagesTypes[object.value].dimensionRules.required;
                }

                if (!dimensionIsRequired) {
                    packageLength.attr('disabled', true);
                    packageWidth.attr('disabled', true);
                    packageHeight.attr('disabled', true);
                    packageLength.val(0.0);
                    packageWidth.val(0.0);
                    packageHeight.val(0.0);
                    dimensionUnitsIn.find('option[value=IN]').attr('selected', 'selected').attr('disabled', true);
                    dimensionUnitsIn.attr('disabled', true);
                }

                if (object.value === optionCustom || dimensionIsRequired) {
                    packageLength.val('');
                    packageWidth.val('');
                    packageHeight.val('');
                    packageLength.attr('disabled', false);
                    packageWidth.attr('disabled', false);
                    packageHeight.attr('disabled', false);
                    dimensionUnitsIn.attr('disabled', false);
                }
            },
            calculateWeight: function (sectionElements) {
                const lbsToOz = 16;

                let lbs = sectionElements.querySelector('input[name=container_weight_lb]').value ?? '0',
                    oz = sectionElements.querySelector('input[name=container_weight_oz]').value ?? '0';


                return (parseInt(lbs, 10) * lbsToOz) + parseInt(oz, 10);
            },
            _recalcContainerWeightAndCustomsValue: function (container) {
                var packageBlock = container.up('[id^="package_block"]');
                var packageId = this.getPackageId(packageBlock);
                var containerWeightLb = packageBlock.select('[name="container_weight_lb"]')[0];
                var containerWeightOz = packageBlock.select('[name="container_weight_oz"]')[0];
                var containerCustomsValue = packageBlock.select('[name="package_customs_value"]')[0];

                containerWeightLb.value = 0;
                containerWeightOz.value = 0;
                containerCustomsValue.value = 0;
                container.select('.grid tbody tr').each(function (item) {
                    var itemId = item.select('[type="checkbox"]')[0].value;
                    var qtyValue = parseFloat(item.select('[name="qty"]')[0].value);

                    if (isNaN(qtyValue) || qtyValue <= 0) {
                        qtyValue = 1;
                        item.select('[name="qty"]')[0].value = qtyValue;
                    }
                    var itemWeight = parseFloat(this._getElementText(item.select('[data-role=item-weight]')[0]));

                    let allWeight = parseFloat(itemWeight * qtyValue);

                    containerWeightLb.value = Math.trunc(allWeight);
                    containerWeightOz.value = Math.ceil((allWeight - Math.trunc(allWeight)) / 0.0625);

                    var itemCustomsValue = parseFloat(item.select('[name="customs_value"]')[0].value) || 0;

                    containerCustomsValue.value = parseFloat(containerCustomsValue.value) + itemCustomsValue * qtyValue;
                    this.packages[packageId]['items'][itemId]['customs_value'] = itemCustomsValue;
                }.bind(this));
                containerWeightLb.value = parseFloat(parseFloat(Math.round(containerWeightLb.value + 'e+4') + 'e-4').toFixed(4));
                containerWeightOz.value = parseFloat(parseFloat(Math.round(containerWeightOz.value + 'e+4') + 'e-4').toFixed(4));
                containerCustomsValue.value = parseFloat(Math.round(containerCustomsValue.value + 'e+2') + 'e-2').toFixed(2);

                if (containerCustomsValue.value == 0) {
                    containerCustomsValue.value = '';
                }
            },
            validate: function (pack) {
                var dimensionElements = pack.select(
                    'input[name=container_length],input[name=container_width],input[name=container_height],input[name=container_girth]:not("._disabled")'
                );
                var callback = null;

                if (dimensionElements.any(function (element) {
                    return !!element.value;
                })) {
                    callback = function (element) {
                        $(element).addClassName('required-entry');
                    };
                } else {
                    callback = function (element) {
                        $(element).removeClassName('required-entry');
                    };
                }
                dimensionElements.each(callback);

                return result = $$('[id^="package_block_"] input').collect(function (element) {
                    return this.validateElement(element);
                }, this).all();
            },
        });
    }
});

