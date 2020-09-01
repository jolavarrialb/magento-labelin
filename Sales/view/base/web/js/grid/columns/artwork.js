define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Labelin_Sales/ui/grid/cells/artwork'
        },

        /**
         * @param {Object} record - Data to be preprocessed.
         * @returns {String}
         */
        getArtwork: function (record) {
            return record['order_artworks'];
        },
    });
});
