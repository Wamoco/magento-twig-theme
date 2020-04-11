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

  $.widget('mage.wishlistAdd',{
    _create: function(config,element) {
      var self = this;
      customerData.get('wishlist').subscribe(self.updateState.bind(self));
      self.updateState(customerData.get('wishlist')());

      $(this.element).on('click', function(e) {
        e.preventDefault();

        if ($(self.element).hasClass("active")) {
          $(self.element).removeClass("active");
          self.remove();
        } else {
          $(self.element).text($.mage.__("Adding..."));
          self.add();
        }
      });
    },
    add: function() {
      if (!customerData.get('customer')().fullname) {
        window.location.href = "/customer/account/login";
        return;
      }
      this.post(`/wishlist/index/add?product=${this.options.productId}&form_key=${this.getFormkey()}&isAjax=true`);
    },
    remove: function() {
      this.post(`/wishlist/index/remove?item=${this.getItemId()}&form_key=${this.getFormkey()}&isAjax=true`);
    },
    post: function(path) {
      $.ajax({
        method: 'POST',
        url: `${path}`,
        headers: {
          'X_REQUESTED_WITH': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        dataType: 'json'
      }).done(this.success).fail(this.success).always(this.success);
    },
    getFormkey: function() {
      return $.mage.cookies.get('form_key');
    },
    success: function(data) {
      customerData.reload();
    },
    getItemId: function() {
      var items = this.getState().items;
      for(var i=0;i<items.length;i++) {
        if (items[i].product_id == this.options.productId) {
          return items[i].item_id;
        }
      }
      return null;
    },
    getState: function() {
      return customerData.get('wishlist')();
    },
    updateState: function(wishlistData) {
      var items = wishlistData.items;
      if (!items) {
        return;
      }
      for(var i=0;i<items.length;i++) {
        if (items[i].product_id == this.options.productId) {
          $(this.element).addClass("active");
          $(this.element).text($.mage.__("Wishlist"));
          return;
        }
      }
    }
  });

  return $.mage.wishlistAdd;
});
