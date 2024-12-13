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
{if isset($type) && $type}
    {if $type == 'icon' && isset($label) && $label}
        <i class="icon-{$label|escape:'quotes'}"></i>&nbsp
    {elseif $type == 'badge' && isset($value) && $value}
        <span class="badge badge-{$label|escape:'html':'utf-8'}">{$value|escape:'html':'utf-8'}</span>
    {elseif $type == 'ETS_GDPR_DELETION_EMAIL'}
        <p>Dear [customer]</p>
        <p>As your request, we have deleted your data. Below are details of data deleted.</p>
        <p>[data_deleted]</p>
        <p>Regards,</p>
        <p>[shop_name]</p>
    {elseif $type == 'ETS_GDPR_REFUSAL_EMAIL'}
        <p>Dear [customer]</p>
        <p>We have checked your request of personal data deletion.<br/>We’re sorry to say, the data could not be deleted due to our data protection terms.<br/> Below are details of data you requested to delete
        </p>
        <p>[data_deleted]</p>
        <p>Regards,</p>
        <p>[shop_name]</p>
    {elseif $type == 'location'}
        <a target="_blank" class="gdpr-link" href="https://db-ip.com/{$label|escape:'quotes'}" >{l s='View location' mod='ets_gdpr'}</a>
    {elseif $type == 'EMAIL_LOGIN'}
        {literal}
        <p>New login on {shop_name},</p>
        <p>It looks like you singed into <a href="{shop_url}" style="color:#337ff1">{shop_name}</a> from a new device.</p>
        <p>Device: [device]</p>
        <p>IP address: [ip]</p>
        <p>If this wasn’t you, let us know.</p>
        {/literal}
    {elseif $type == 'EMAIL_MODIFIED'}
        <p>{l s='Your account modified by' mod='ets_gdpr'} {$value.modified_by|escape:'html':'utf-8'}</p>
        <p>{l s='Data type' mod='ets_gdpr'}: <strong>{$value.data_modified|strip_tags|escape:'html':'utf-8'}</strong></p>
        <p>{l s='Modified information' mod='ets_gdpr'}:</p>
        <p>{if isset($value.link_order) && $value.link_order}<a href="{$value.link_order|escape:'quotes'}" title="{l s='View order' mod='ets_gdpr'}">{l s='View order' mod='ets_gdpr'}</a>{else}{$value.details nofilter}{/if}</p>
        {if isset($label) && $label != 'admin'}
            <p>{l s='If it is not you, please contact us immediately for support. Thank you' mod='ets_gdpr'}</p>
        {/if}
    {elseif $type == 'SHOP_NAME'}
        {literal}
            <a href="{shop_url}" style="color:#337ff1">{shop_name}</a>
        {/literal}
    {elseif $type == 'EMAIL_REQUEST'}
        <p>{l s='New data deletion request' mod='ets_gdpr'} #[itemId]</p>
        <p>{l s='Hi' mod='ets_gdpr'} {literal}{shop_name}{/literal}</p>
        <p>{l s='A customer just sent an request to delete their personal data on your website. Details are listed below:' mod='ets_gdpr'}</p>
        <p>{l s='Request ID' mod='ets_gdpr'}: [itemId]</p>
        <p>{l s='Customer name' mod='ets_gdpr'}: [full_name]</p>
        <p>{l s='Customer email' mod='ets_gdpr'}: [email]</p>
        <p>{l s='Data that customer want to delete:' mod='ets_gdpr'}</p>
        <p>[data_modified]</p>
        <p>{l s='Log into the back office to approve/disapprove the request' mod='ets_gdpr'}</p>
    {elseif $type == 'DESC_TAG'}
        {l s='Available tags' mod='ets_gdpr'}: <span class="gdpr_tag">[customer]</span>, <span class="gdpr_tag">[data_deleted]</span>, <span class="gdpr_tag">[shop_name]</span>
    {elseif $type=='LINK_TAB_MES' && isset($link_tab) && $link_tab}
        <a href="{$link_tab|escape:'html':'utf-8'}">{l s='You can edit GDPR notification for each position above in "GDPR Notification / Messages" tab' mod='ets_gdpr'}</a>
    {elseif $type == 'NOTICE_TOP'}
        <p>Our Privacy Policy explains our principles when it comes to the collection, processing, and storage of your information. This policy specifically explains how we employ cookies, as well as the options you have to control them.</p>
    {elseif $type == 'NOTICE_BOTTOM'}
        <p>We are committed to maintaining the trust and confidence of our website visitors. We do not collect, sell, rent or trade email lists or any data with other companies and businesses. Have a look at our Privacy Policy page to read detail information on when and why we collect your personal information, how we use it, the limited conditions under which we may disclose it to others and how we keep it secure.</p>
        <p>We may change Cookies and Privacy policy from time to time. This policy is effective from 24th May 2018.</p>
    {elseif $type == 'NOTICE_ITEMS'}
        {if isset($label) && $label}
            {if $label == 1}<p>Cookies are small pieces of data, stored in text files that are stored on your computer or other device when websites are loaded in a browser. They are widely used to "remember" you and your preferences, either for a single visit or for multiple repeat visits</p>{/if}
            {if $label == 2}<p>We use cookies for a number of different purposes. Some cookies are necessary for technical reasons; some enable a personalized experience for both visitors and registered users; and some allow the display of advertising from selected third party networks.</p>{/if}
            {if $label == 3}<p>Visitors may wish to restrict the use of cookies or completely prevent them from being set. If you disable cookies, please be aware that some of the features of our service may not function correctly</p>{/if}
            {if $label == 4}<p>We only collect information about you if we have a reason to do so-for example, to provide our services, to communicate with you, or to make our services better.</p>{/if}
        {/if}
    {/if}
{/if}