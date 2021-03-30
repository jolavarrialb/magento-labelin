define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Labelin_Sales/ui/grid/cells/product_type'
        },

        /**
         * @param {Object} record - Data to be preprocessed.
         * @returns {String}
         */
        getProductType: function (record) {
            return record['product_type'];
        },
    });
});
