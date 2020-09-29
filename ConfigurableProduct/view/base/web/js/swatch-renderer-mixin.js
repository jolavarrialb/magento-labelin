define([
    'jquery'
], function ($) {
    'use strict';

    var swatchRenderMixin = {
        _RenderControls: function () {
            localStorage.removeItem('sticker_current_step');

            var $widget = this,
                container = this.element,
                classes = this.options.classes,
                chooseText = this.options.jsonConfig.chooseText,
                showTooltip = false;

            $widget.optionsMap = {};

            var optionCount = 0;

            $.each(this.options.jsonConfig.attributes, function () {
                var item = this,
                    controlLabelId = 'option-label-' + item.code + '-' + item.id,
                    options = $widget._RenderSwatchOptions(item, controlLabelId),
                    select = $widget._RenderSwatchSelect(item, chooseText),
                    input = $widget._RenderFormInput(item),
                    listLabel = '',
                    label = '',
                    display = optionCount > 0 ? ' style="display:none" ' : ' style="display:block" ',
                    controls = '',
                    additionClass = '',
                    additionBlock = '',
                    header = '',
                    headerInfo = '',
                    wrapper = false,
                    step = 'data-step="' + (optionCount + 1) + '"';

                optionCount++;

                // Show only swatch controls
                if ($widget.options.onlySwatches && !$widget.options.jsonSwatchConfig.hasOwnProperty(item.id)) {
                    return;
                }

                if ($widget.options.enableControlLabel) {
                    label +=
                        '<span id="' + controlLabelId + '" class="' + classes.attributeLabelClass + '">' +
                        $('<i></i>').text(item.label).html() +
                        '</span>' +
                        '<span class="' + classes.attributeSelectedOptionLabelClass + '"></span>';
                }

                if ($widget.inProductList) {
                    $widget.productForm.append(input);
                    input = '';
                    listLabel = 'aria-label="' + $('<i></i>').text(item.label).html() + '"';
                } else {
                    listLabel = 'aria-labelledby="' + controlLabelId + '"';
                }

                // Create new control

                if (item.code === 'sticker_size') {

                    header = $widget.options.optionSizeHeader.header ? $widget.options.optionSizeHeader.header : '';
                    headerInfo = $widget.options.optionSizeHeader.headerInfo ? $widget.options.optionSizeHeader.headerInfo : '';
                    additionClass = 'radiobuttons-wrapper';
                    let url = $widget.options.additionalSizeBlockImgUrl ? $widget.options.additionalSizeBlockImgUrl : '#';
                    wrapper = true;
                    additionBlock = `
                                    <div class="set-size-image-wrapper">
                                        <img src="${url}"/>
                                    </div>
                    `;

                } else if (item.code === 'sticker_shape') {
                    header = $widget.options.optionShapeHeader.header ? $widget.options.optionShapeHeader.header : '';
                    headerInfo = $widget.options.optionShapeHeader.headerInfo ? $widget.options.optionShapeHeader.headerInfo : '';

                } else if (item.code === 'sticker_type') {
                    header = $widget.options.optionTypeHeader.header ? $widget.options.optionTypeHeader.header : '';
                    headerInfo = $widget.options.optionTypeHeader.headerInfo ? $widget.options.optionTypeHeader.headerInfo : '';
                }

                const headerTemplate = `
                            <div class="header-wrapper">
                                <h2 class="checkout-page-header">
                                    ${header}
                                </h2>
                                <p class="checkout-page-text">
                                    ${headerInfo}
                                </p>
                            </div>
                        `;

                controls = `
                    <div class="${classes.attributeClass} ${item.code} "
                         attribute-code="${item.code}"
                         attribute-id="${item.id}" ${display} ${step}
                    >
                    ${headerTemplate}
                    ${label}
                        ${wrapper ? `<div class="set-size-wrapper">` : ''}
                        <div aria-activedescendant=""
                             tabindex="0"
                             aria-invalid="false"
                             aria-required="true"
                             role="listbox"
                             ${listLabel}
                             class="${classes.attributeOptionsWrapper} ${additionClass} clearfix">
                             ${options}
                             ${select}
                        </div>
                        ${additionBlock}
                        ${input}
                        ${wrapper ? "<\div>" : ''}
                    </div>
                `;

                container.append(controls);

                $widget.optionsMap[item.id] = {};

                Array
                    .from(Array(10).keys())
                    .forEach(element => localStorage.removeItem('data-step-' + element))

                // Aggregate options array to hash (key => value)
                $.each(item.options, function () {
                    if (this.products.length > 0) {
                        $widget.optionsMap[item.id][this.id] = {
                            price: parseInt(
                                $widget.options.jsonConfig.optionPrices[this.products[0]].finalPrice.amount,
                                10
                            ),
                            products: this.products
                        };
                    }
                });
            });

            if (showTooltip === 1) {
                // Connect Tooltip
                container
                    .find('[option-type="1"], [option-type="2"], [option-type="0"], [option-type="3"]')
                    .SwatchRendererTooltip();
            }

            // Hide all elements below more button
            $('.' + classes.moreButton).nextAll().hide();

            // Handle events like click or change
            $widget._EventListener();

            // Rewind options
            $widget._Rewind(container);

            localStorage.setItem('sticker_current_step', "1");

            let fullRender = document.createEvent('Event');
            fullRender.initEvent('swatch-full-render', true, true);

            document.dispatchEvent(fullRender);
        },
        _OnClick: function ($this, $widget) {
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                $wrapper = $this.parents('.' + $widget.options.classes.attributeOptionsWrapper),
                $label = $parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                attributeId = $parent.attr('attribute-id'),
                $input = $parent.find('.' + $widget.options.classes.attributeInput),
                checkAdditionalData = JSON.parse(this.options.jsonSwatchConfig[attributeId]['additional_data']);

            if ($widget.inProductList) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }

            if ($this.hasClass('disabled')) {
                return;
            }

            if ($this.hasClass('selected')) {
                $parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                $input.val('');
                $label.text('');
                $this.attr('aria-checked', false);

                $this.find("input[name='sticker_size']").first().prop("checked", false);

                let selectSwatch = document.createEvent('Event');
                selectSwatch.initEvent('swatch-unselect-option', true, true);

                document.dispatchEvent(selectSwatch);
            } else {
                $parent.attr('option-selected', $this.attr('option-id')).find('.selected').removeClass('selected');
                $label.text($this.attr('option-label'));
                $input.val($this.attr('option-id'));
                $input.attr('data-attr-name', this._getAttributeCodeById(attributeId));
                $this.addClass('selected');
                $widget._toggleCheckedAttributes($this, $wrapper);

                $this.find("input[name='sticker_size']").first().prop("checked", true);

                localStorage.setItem('data-step-' + localStorage.getItem('sticker_current_step'), $label.text());

                let selectSwatch = document.createEvent('Event');
                selectSwatch.initEvent('swatch-select-option', true, true);

                document.dispatchEvent(selectSwatch);
            }

            $widget._Rebuild();

            if ($widget.element.parents($widget.options.selectorProduct)
                .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
            ) {
                $widget._UpdatePrice();
            }

            $(document).trigger('updateMsrpPriceBlock',
                [
                    _.findKey($widget.options.jsonConfig.index, $widget.options.jsonConfig.defaultValues),
                    $widget.options.jsonConfig.optionPrices
                ]);

            if (parseInt(checkAdditionalData['update_product_preview_image'], 10) === 1) {
                $widget._loadMedia();
            }

            $input.trigger('change');
        },
        _Rebuild: function () {
            var $widget = this,
                controls = $widget.element.find('.' + $widget.options.classes.attributeClass + '[attribute-id]'),
                selected = controls.filter('[option-selected]');

            // Enable all options
            $widget._Rewind(controls);

            // done if nothing selected
            if (selected.length <= 0) {
                return;
            }

            // Disable not available options
            controls.each(function () {
                var $this = $(this),
                    id = $this.attr('attribute-id'),
                    products = $widget._CalcProducts(id);

                if (selected.length === 1 && selected.first().attr('attribute-id') === id) {
                    return;
                }

                $this.find('[option-id]').each(function () {
                    var $element = $(this),
                        option = $element.attr('option-id');

                    if (!$widget.optionsMap.hasOwnProperty(id) || !$widget.optionsMap[id].hasOwnProperty(option) ||
                        $element.hasClass('selected') ||
                        $element.is(':selected')) {
                        return;
                    }

                    if (_.intersection(products, $widget.optionsMap[id][option].products).length <= 0) {
                        $element.attr('disabled', true).addClass('disabled').hide();
                    }
                });
            });
        },
        _Rewind: function (controls) {
            controls.find('div[option-id], option[option-id]').removeClass('disabled').removeAttr('disabled').show();
            controls.find('div[option-empty], option[option-empty]')
                .attr('disabled', true)
                .addClass('disabled')
                .attr('tabindex', '-1')
                .hide();
        },
        _RenderSwatchOptions: function (config, controlId) {
            var optionConfig = this.options.jsonSwatchConfig[config.id],
                optionClass = this.options.classes.optionClass,
                sizeConfig = this.options.jsonSwatchImageSizeConfig,
                moreLimit = parseInt(this.options.numberToShow, 10),
                moreClass = this.options.classes.moreButton,
                moreText = this.options.moreButtonText,
                countAttributes = 0,
                html = '',
                optionClassWithCode = config.code,
                optionTypeTooltipObject = this.options.optionTypeTooltips,
                optionTypeTooltip = '';

            if (!this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }

            $.each(config.options, function (index) {

                var id,
                    type,
                    value,
                    thumb,
                    label,
                    width,
                    height,
                    attr,
                    swatchImageWidth,
                    swatchImageHeight;


                if (!optionConfig.hasOwnProperty(this.id)) {
                    return '';
                }

                // Add more button
                if (moreLimit === countAttributes++) {
                    html += '<a href="#" class="' + moreClass + '"><span>' + moreText + '</span></a>';
                }

                id = this.id;
                type = parseInt(optionConfig[id].type, 10);
                value = optionConfig[id].hasOwnProperty('value') ?
                    $('<i></i>').text(optionConfig[id].value).html() : '';
                thumb = optionConfig[id].hasOwnProperty('thumb') ? optionConfig[id].thumb : '';
                width = _.has(sizeConfig, 'swatchThumb') ? sizeConfig.swatchThumb.width : 110;
                height = _.has(sizeConfig, 'swatchThumb') ? sizeConfig.swatchThumb.height : 90;
                label = this.label ? $('<i></i>').text(this.label).html() : '';
                attr =
                    ' id="' + controlId + '-item-' + id + '"' +
                    ' index="' + index + '"' +
                    ' aria-checked="false"' +
                    ' aria-describedby="' + controlId + '"' +
                    ' tabindex="0"' +
                    ' option-type="' + type + '"' +
                    ' option-id="' + id + '"' +
                    ' option-label="' + label + '"' +
                    ' aria-label="' + label + '"' +
                    ' option-tooltip-thumb="' + thumb + '"' +
                    ' option-tooltip-value="' + value + '"' +
                    ' role="option"' +
                    ' thumb-width="' + width + '"' +
                    ' thumb-height="' + height + '"';

                swatchImageWidth = _.has(sizeConfig, 'swatchImage') ? sizeConfig.swatchImage.width : 30;
                swatchImageHeight = _.has(sizeConfig, 'swatchImage') ? sizeConfig.swatchImage.height : 20;

                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }

                if (type === 0) {
                    // Text
                    let valueText = value ? value : label;
                    const sizeTypeTemplate = `
                        <div class="radio-container ${optionClass} text" ${attr} for="radio-${id}">
                            <input
                                type="radio"
                                id="radio-${id}"
                                name="sticker_size"
                                value="${valueText}"
                                class="radiobutton"
                            >
                                <label>${valueText}</label>
                        </div>
            `;
                    html += sizeTypeTemplate;
                } else if (type === 1) {
                    // Color
                    html += '<div class="' + optionClass + ' color" ' + attr +
                        ' style="background: ' + value +
                        ' no-repeat center; background-size: initial;">' + '' +
                        '</div>';
                } else if (type === 2) {
                    // Image
                    if (typeof optionTypeTooltipObject[label] !== "undefined") {
                        optionTypeTooltip = optionTypeTooltipObject[label];
                    }

                    let imageTemplate = `
                        <div option-id="${id}" class="card-wrapper">
                            <div class="${optionClass} image ${optionClassWithCode}"
                                ${attr}
                                style="background-image: url('${value}'); width: ${swatchImageWidth}px; height: ${swatchImageHeight}px"/>
                            <div class="card-text">
                                <p>${label}</p>
                                ${optionTypeTooltip}
                            </div>
                        </div>
                    `;
                    html += imageTemplate;
                } else if (type === 3) {
                    // Clear
                    html += '<div class="' + optionClass + '" ' + attr + '></div>';
                } else {
                    // Default
                    html += '<div class="' + optionClass + '" ' + attr + '>' + label + '</div>';
                }
            });

            return html;
        },
    };

    return function () {
        $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, swatchRenderMixin);

        return $.mage.SwatchRenderer;
    };
});
