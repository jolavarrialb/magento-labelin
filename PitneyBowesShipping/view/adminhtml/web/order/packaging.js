define([
    'jquery',
    'Magento_Shipping/order/packaging'
], function ($) {
    'use strict';

    if (window.packaging !== "undefined") {
        (function (sendCreateLabelRequest) {
            Packaging.prototype.sendCreateLabelRequest = function () {

                var self = this;

                if (!this.validate()) {
                    this.messages.show().update(this.validationErrorMsg);

                    return;
                }
                this.messages.hide().update();

                if (this.createLabelUrl) {
                    var weight, length, width, height, service = null;
                    var packagesParams = [];

                    this.packagesContent.childElements().each(function (pack) {
                        var packageId = this.getPackageId(pack);

                        weight = parseFloat(pack.select('input[name="container_weight"]')[0].value);
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
                            weight_units: pack.select('select[name="container_weight_units"]')[0].value,
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
            };
        })(Packaging.prototype.sendCreateLabelRequest);
    }
});

