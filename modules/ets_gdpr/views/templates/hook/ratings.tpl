{*
 * Copyright ETS Software Technology Co., Ltd
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.
 *
 * @author ETS Software Technology Co., Ltd
 * @copyright  ETS Software Technology Co., Ltd
 * @license    Valid for 1 website (or project) for each purchase of license
*}
{if isset($grade) && $grade}{for $star = 1 to 5}{if $grade < $star}{if $export}<img width="10" height="10" src="{$img_dir|escape:'quotes'}star-o.jpg"/>{else}<div class="{if $is_admin}icon icon{else}fa fa{/if}-star-o"></div>{/if}{else}{if $export}<img width="10" height="10" class="star star_on" src="{$img_dir|escape:'quotes'}star.jpg" />{else}<div class="{if $is_admin}icon icon{else}fa fa{/if}-star"></div>{/if}{/if}{/for}{/if}