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
                showTooltip = this.options.showTooltip;

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
                container.append(
                    '<div class="' + classes.attributeClass + ' ' + item.code + '" ' +
                    'attribute-code="' + item.code + '" ' +
                    'attribute-id="' + item.id + '"' + display + step + '>' +
                    label +
                    '<div aria-activedescendant="" ' +
                    'tabindex="0" ' +
                    'aria-invalid="false" ' +
                    'aria-required="true" ' +
                    'role="listbox" ' + listLabel +
                    'class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                    options + select +
                    '</div>' + input +
                    '</div>'
                );

                $widget.optionsMap[item.id] = {};

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

            //Emulate click on all swatches from Request
            $widget._EmulateSelected($.parseQuery());
            $widget._EmulateSelected($widget._getSelectedAttributes());

            localStorage.setItem('sticker_current_step', "1");

            let fullRender = document.createEvent('Event');
            fullRender.initEvent('swatch-full-render', true, true);

            document.dispatchEvent(fullRender);
        },
    };

    return function () {
        $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, swatchRenderMixin);

        return $.mage.SwatchRenderer;
    };
});
