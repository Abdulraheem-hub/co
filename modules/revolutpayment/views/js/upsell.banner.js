/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    Revolut
 * @copyright Since 2020 Revolut
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 */

jQuery(function ($) {
  function initPromotionalBanner() {
    const target = document.getElementById("upsellPromotionalBanner");

    if (!target) {
      return null;
    }

    let transactionId = target.dataset.bannerTransactionId;
    let publicToken = target.dataset.bannerMerchantPublicKey;
    let currency = target.dataset.bannerCurrency;

    let customer = {
      email: target.dataset.bannerCustomerEmail,
      phone: target.dataset.bannerCustomerPhone,
    };

    RevolutUpsell = RevolutUpsell({
      locale: "auto",
      publicToken: publicToken,
    });

    RevolutUpsell.promotionalBanner.mount(target, {
      transactionId: transactionId,
      currency: currency,
      customer: customer,
      __metadata: { channel: 'prestashop' },
    });
  }

  function initEnrollmentConfirmationBanner() {
    const target = document.getElementById("upsellEnrollmentConfirmationBanner");

    if (!target) {
      return null;
    }

    let publicToken = target.dataset.bannerMerchantPublicKey;
    let orderPublciId = target.dataset.bannerOrderPublicId;

    RevolutUpsell = RevolutUpsell({
      locale: "auto",
      publicToken: publicToken,
    });

    RevolutUpsell.enrollmentConfirmationBanner.mount(target, {
      orderToken: orderPublciId,
      promotionalBanner: true,
      __metadata: { channel: 'prestashop' },
    });
  }

  initPromotionalBanner();
  initEnrollmentConfirmationBanner();
});
