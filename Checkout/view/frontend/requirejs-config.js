var config = {
    map: {
        '*': {
            'Magento_Checkout/template/minicart/item/default.html': 'Labelin_Checkout/template/minicart/item/default.html',
            'Magento_Checkout/template/minicart/content.html': 'Labelin_Checkout/template/minicart/content.html',
            'update-cart-by-select-qty-change': 'Labelin_Checkout/js/update-cart-by-select-qty-change'
        }
    },
    shim: {
        'update-cart-by-select-qty-change': ['jquery']
    }
};
