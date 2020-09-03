define([
    'uiElement',
    'uiComponent'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {},
        titleFun1: function () {
            return 'atataTas';
        },
        initialize: function () {
            console.dir(this.titleFun1())
        },

    });
});
