
define([
    'Magento_Checkout/js/view/shipping-information/address-renderer/default',
    'ko',
    'jquery',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/model/sidebar'
], function (
    Component,
    ko,
    $,
    stepNavigator,
    sidebarModel
) {
    return Component.extend({

        initObservable : function () {
            return this;
            this._super().observe([
                'address',
                'isVisible'
            ]);

            this.address =  ko.observable(null);

            if (typeof window.dpdShippingAddress === undefined) {
                return this;
            }


            this.address = window.dpdShippingAddress;
            console.log(window.dpdShippingAddress);
            /*this.isVisible = ko.computed(function () {
                return State.currentSelectedShipmentType() === 'pickup';
            });
            */

            return this;
        },

        address: function () {

        },

        back: function () {
            sidebarModel.hide();
            stepNavigator.navigateTo('shipping');
        }
    });
});