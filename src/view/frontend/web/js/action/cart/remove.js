/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
define([
  'jquery',
  'Magento_Customer/js/customer-data',
  'mage/cookies'
], function ($, customerData) {
  'use strict';

  $.widget('mage.cartRemove',{
    _create: function(config,element) {
      var itemId = this.options.itemId;
      $(this.element).on('click', function() {
        var formkey = $.mage.cookies.get('form_key');

        $.ajax({
          method: 'POST',
          url: `/checkout/sidebar/removeItem/item_id/${itemId}/form_key/${formkey}/?isAjax=true`,
          headers: {
            'X_REQUESTED_WITH': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          dataType: 'json'
        }).done(function(data){
          customerData.update(data);
        });
      });
    }
  });

  return $.mage.cartRemove;

});
