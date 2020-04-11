/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
define([
  'jquery',
  'twig',
  'Magento_Customer/js/customer-data',
  'mage/apply/main',
  'Magento_Catalog/js/price-utils',
  'mage/url',
  'mage/translate'
], function ($, Twig, customerData, mage, PriceUtils, UrlBuilder) {
  'use strict';

  Twig.extendFilter("price", function(price) {
      return PriceUtils.formatPrice(price, window.priceFormat);
  });
  Twig.extendFilter("trans", function(value) {
      return $.mage.__(value);
  });
  Twig.extendFunction("getBaseUrl", function() {
      return window.BASE_URL;
  });
  Twig.extendFunction("getViewFileUrl", function() {
      return window.require.baseUrl;
  });
  Twig.extendFunction("getAsset", function(path) {
      return window.WEB_URL + path;
  });
  Twig.extendFunction("getUrl", function(path) {
      return UrlBuilder.build(path);
  });

  $.widget('mage.TwigTemplate',{
    options: {
        path: null,
        template: null
    },

    _create: function(config,element) {
        // fetchTemplate
        if (this.options.path) {
            this.options.template = this.fetchTemplate(this.options.path);
        }
        this.render();
    },

    render: function() {
        var self = this;

        customerData.get('cart').subscribe(self.render.bind(self));
        customerData.get('customer').subscribe(self.render.bind(self));
        customerData.get('wishlist').subscribe(self.render.bind(self));
        customerData.get('messages').subscribe(self.render.bind(self));

        var state = {
          cart: customerData.get('cart')(),
          customer: customerData.get('customer')(),
          wishlist: customerData.get('wishlist')(),
          messages: customerData.get('messages')()
        };

        // only render if something loaded
        if (this.options.template) {
          var t = Twig.twig({
              data: this.options.template
          });
          t.trace = true;
          t.debug = true;
          var output = t.render(state);
          $(this.element).html(output);
          mage.apply();
        }
    },

    fetchTemplate: function (path) {
      var res = $.ajax({ url: `${window.TEMPLATES_URL}/${path}`, async: false});
      return res.responseText;
    }
  });

  return $.mage.TwigTemplate;
});
