{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{*<div class="product-additional-info js-product-additional-info">
  {hook h='displayProductAdditionalInfo' product=$product}
</div>*}
<div class="product-additional-info">
	<!--added by skt-->
					<section id="product-details-wrapper" class="product-details-section block-section">
            <!--<h4 class="section-title"><span>{l s='Product Details' d='Shop.Theme.Catalog'}</span></h4>-->
            <div class="section-content">
                {block name='product_details'}
                    {include file='catalog/_partials/product-short-details.tpl'}
                {/block}
              </div>
      </section>
<!--ended by skt-->
  {hook h='displayProductAdditionalInfo' product=$product}
</div>
