/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'braspag_pagador_billet',
                component: 'Webjump_BraspagPagador/js/view/payment/method-renderer/billet'
            },
            {
                type: 'braspag_pagador_creditcard',
                component: 'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard'
            }
        );
        return Component.extend({});
    }
);