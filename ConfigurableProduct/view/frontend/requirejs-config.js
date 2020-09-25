var config = {
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Labelin_ConfigurableProduct/js/swatch-renderer-mixin': true
            }
        }
    },
    map: {
        '*': {
            Labelin_ConfigurableProduct_Step_Quantity:'Labelin_ConfigurableProduct/js/step-quantity'
        }
    },
    shim: {
        'Labelin_ConfigurableProduct/js/step-quantity': ['jquery'],
        'Labelin_ConfigurableProduct/js/step-upload-image': []
    }
};
