/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
define([
  'jquery',
  'Magento_Customer/js/customer-data',
  'mage/translate',
  'mage/cookies'
], function ($, customerData) {
  'use strict';

  // Trigger translations
  // $.mage.__("Shopping Cart");

  $.widget('mage.cartAdd',{
    _create: function(config,element) {
      var productId = this.options.productId;
      var self = this;

      $(this.element).on('click', function(e) {
        var formkey = $.mage.cookies.get('form_key');
        e.preventDefault();

        var label = $(self.element).text();
        $(self.element).addClass("loading");
        $(self.element).text($.mage.__("Adding..."));

        $.ajax({
          method: 'POST',
          url: `/checkout/cart/add?product=${productId}&form_key=${formkey}&isAjax=true`,
          headers: {
            'X_REQUESTED_WITH': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          dataType: 'json'
        }).done(function(data){
          if (data.backUrl) {
            window.location.href = data.backUrl;
            return false;
          }
          customerData.reload();
          $(self.element).removeClass("loading");
          $(self.element).addClass("added");
          $(self.element).text(label);
        });
        return false;
      });
    }
  });

  return $.mage.cartAdd;
});
