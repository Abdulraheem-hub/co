/**
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
 */

var ets_gdpr_func = {
    closeForm: function () {
        if ($('.gdpr_notice.actived').length > 0)
            $('.gdpr_notice.actived').removeClass('actived');
    },
    updateAccept: function (button, accept) {
        if (button.hasClass('login')) {
            //17, 16.
            if ($('#submit-login, #SubmitLogin').length > 0) {
                $('#submit-login, #SubmitLogin').prop('disabled', !accept);
            }
        } else if (button.hasClass('register')) {
            //17,16
            if ($('#customer-form button[type=submit], #account-creation_form button[type=submit]').length > 0) {
                $('#customer-form button[type=submit], #account-creation_form button[type=submit]').prop('disabled', !accept);
            }
        } else if (button.hasClass('subscribe')) {
            //17, 16
            if ($('.block_newsletter form input[type=submit], #newsletter_block_left form button[type=submit]').length > 0) {
                $('.block_newsletter form input[type=submit], #newsletter_block_left form button[type=submit]').prop('disabled', !accept);
            }
        } else if (button.hasClass('contact')) {
            //17, 16
            if ($('.contact-form form input[type=submit], #submitMessage').length > 0) {
                $('.contact-form input[type=submit], #submitMessage').prop('disabled', !accept);
            }
        } else if (button.hasClass('receive')) {
            //17, 16
            if ($('.js-mailalert button[type=submit], #mailalert_link').length > 0) {
                $('.js-mailalert button[type=submit], #mailalert_link').prop('disabled', !accept);
            }
        }
    },
    policy: function (button, accept) {
        if (!button.hasClass('actived') && typeof link_accept != "undefined") {
            if ($('.gdpr_notice.actived').length > 0) {
                $('.gdpr_notice.actived').removeClass('actived');
            }
            if ($('.gdpr_hook.gdpr_wrapper').length > 0) {
                $('.gdpr_hook.gdpr_wrapper').hide();
            }
            $.ajax({
                url: link_accept,
                type: 'post',
                dataType: 'json',
                data: {
                    ajax: true,
                    accept: accept,
                    action: 'Acceptance',
                },
                success: function (json) {
                    button.removeClass('actived');
                    if (json == null)
                        return false;
                    if (button.hasClass('gdpr_btn_decline') && typeof declineUrl != "undefined" && declineUrl != '') {
                        window.location.href = declineUrl;
                    }
                },
                error: function () {
                    button.removeClass('actived');
                }
            });
        }
    },
    checkAlls: function (select, item) {
        if (select.length > 0 && item.length > 0) {
            if (select.is(':checked')) {
                item.prop('disabled', true);
                if (typeof select.attr('onclick') == "undefined") {
                    item.prop('checked', true);
                    if (item.parent('span').length > 0)
                        item.parent('span').addClass('checked');
                }
            } else {
                item.prop('disabled', false);
                if (typeof select.attr('onclick') == "undefined") {
                    item.prop('checked', false);
                    if (item.parent('span').length > 0)
                        item.parent('span').removeClass('checked');
                }

            }
        }
    },
    outOfStock: function () {
        if ($('.tabs .js-mailalert, #oosHook').length > 0 && $('.gdpr_hook.receive').length > 0) {
            var receive = $('.gdpr_hook.receive').clone(true);
            if ($('.tabs .js-mailalert > button[type=submit]').length > 0) {
                $('.tabs .js-mailalert > button[type=submit]').before(receive);
            } else if ($('.tabs .js-mailalert > button[type=submit]').length > 0) {
                $('.tabs .js-mailalert > button[type=submit]').first().before(receive);
            } else if ($('#mailalert_link').length > 0 || $('#pa_mailalert_link').length > 0) {
                $('#mailalert_link, #pa_mailalert_link').before(receive);
            } else
                $('.gdpr_hook.receive').detach();

            if ($('.tabs .js-mailalert .gdpr_hook.receive, #oosHook .gdpr_hook.receive').length > 0) {
                $('.tabs .js-mailalert .gdpr_hook.receive').first().addClass('clone');
                $('#mailalert_link, #pa_mailalert_link').prev('.gdpr_hook.receive').addClass('clone');
                $('.gdpr_hook.receive:not(.clone)').remove();
                $('.tabs .js-mailalert button[type=submit], #mailalert_link, #pa_mailalert_link').prop('disabled', true);
            }
        } else if ($('.gdpr_hook.receive').length > 0) {
            $('.gdpr_hook.receive').detach();
        }
        $('.tabs .js-mailalert button[type=submit], #mailalert_link, #pa_mailalert_link').click(function (evt) {
            if ($('.gdpr_hook.receive').length > 0 && !$('.gdpr_hook.receive .gdpr_accept').is(':checked')) {
                evt.preventDefault();
                $(this).prop('disabled', true);
                return false;
            } else if ($('.gdpr_hook.receive.clone .gdpr_accept').is(':checked')) {
                $(this).prop('disabled', false);
            }
        });
    },
};
$(document).ready(function () {
    if ($('.gdpr_hook.gdpr_wrapper.top').length > 0 && $('.gdpr_hook.gdpr_wrapper.top.copy').length <= 0) {
        $('body').prepend($('.gdpr_hook.gdpr_wrapper.top').first().clone(true).addClass('copy'));
        $('.gdpr_hook.gdpr_wrapper.top:not(.copy)').detach();
    }
    $('#withdraw_request').click(function () {
        if (typeof gdpr_warning_withdraw != "undefined" && confirm(gdpr_warning_withdraw))
            return true;
        return false;
    });
    $('button[name=submitDelData]').click(function () {
        if ($('input[name*=dataType]:checked').length <= 0) {
            alert(gdpr_msg_detele_data_error);
            return false;
        } else if ($('input[name=password]').val() == '') {
            alert(gdpr_msg_password_error);
            return false;
        } else if (typeof gdpr_warning_delete != "undefined" && confirm(gdpr_warning_delete))
            return true;
        return false;
    });

    if ($('#dataType_ALL').length > 0) {
        $('#dataType_ALL').click(function () {
            ets_gdpr_func.checkAlls($(this), $('input[id^=dataType]:not([id$=ALL])'));
        });
    }
    $('.gdpr_viewmore').click(function (e) {
        e.preventDefault();
        if ($('.gdpr_notice').length > 0 && $('.gdpr_notice.actived').length <= 0) {
            $('.gdpr_notice').eq(0).addClass('actived');
        }
    });
    $(window).load(function () {
        if ($(window).width() > 767 && $('.gdpr_notice_wap').length > 0 && $('.gdpr_notice_wap').height() >= ($(window).height() - 155)) {
            $('.gdpr_notice_wap').css('height', '90%');
        } else if ($('.gdpr_notice_wap').length > 0 && $('.gdpr_notice_wap').height() >= ($(window).height() - 185)) {
            $('.gdpr_notice_wap').css('height', '90%');
        }
    });
    $(window).resize(function (e) {
        if ($('.gdpr_notice_wap').length > 0 && $('.gdpr_notice_wap').height() >= ($(window).height() - 155)) {
            $('.gdpr_notice_wap').css('height', '90%');
        }
    });
    $('.gdpr_btn_accept').click(function () {
        ets_gdpr_func.policy($(this), 1);
    });
    $('.gdpr_btn_decline').click(function () {
        ets_gdpr_func.policy($(this), 0);
    });
    $(document).keyup(function (e) {
        if (e.keyCode === 27) {
            ets_gdpr_func.closeForm();
        }
    });
    $(document).mouseup(function (e) {
        var popup = $('.gdpr_notice_wap');
        if (!popup.is(e.target) && popup.has(e.target).length === 0)
            ets_gdpr_func.closeForm();
    });
    //subscribe.
    if ($('.block_newsletter form, #newsletter_block_left form').length > 0 && $('.gdpr_hook.subscribe').length > 0) {
        $('.block_newsletter form .row > div, #newsletter_block_left form button[type=submit]').first().after($('.gdpr_hook.subscribe').clone(true));
        if ($('.block_newsletter form .row .gdpr_hook.subscribe, #newsletter_block_left form .gdpr_hook.subscribe').length > 0) {
            $('.block_newsletter form .row .gdpr_hook.subscribe, #newsletter_block_left form .gdpr_hook.subscribe').addClass('clone');
            $('.gdpr_hook.subscribe:not(.clone)').remove();
            $('.block_newsletter form input[type=submit], #newsletter_block_left form button[type=submit]').prop('disabled', true);
        }
    } else if ($('.gdpr_hook.subscribe').length > 0) {
        $('.gdpr_hook.subscribe').detach();
    }
    $('.block_newsletter form input[type=submit], #newsletter_block_left form button[type=submit]').click(function (evt) {
        if ($('.gdpr_hook.subscribe').length > 0 && !$('.gdpr_hook.subscribe .gdpr_accept').is(':checked')) {
            evt.preventDefault();
            $(this).prop('disabled', true);
            return false;
        } else if ($('.gdpr_hook.subscribe.clone .gdpr_accept').is(':checked')) {
            $(this).prop('disabled', false);
        }
    });
    //contact - form.
    if (($('.contact-form').length > 0 || $('.contact-form-box').length > 0) && $('.gdpr_hook.contact').length > 0) {
        var contact = $('.gdpr_hook.contact').clone(true);
        $('.contact-form form .form-fields > div').last().after(contact);
        $('.contact-form-box fieldset > .submit').last().before(contact);
        if ($('.contact-form form .form-fields .gdpr_hook.contact, .contact-form-box fieldset > .gdpr_hook.contact').length > 0) {
            $('.contact-form form .form-fields .gdpr_hook.contact, .contact-form-box fieldset > .gdpr_hook.contact').addClass('clone');
            $('.gdpr_hook.contact:not(.clone)').remove();
            $('.contact-form form input[type=submit], .contact-form-box fieldset button[type=submit]').prop('disabled', true);
        }
    } else if ($('.gdpr_hook.contact').length > 0) {
        $('.gdpr_hook.contact').detach();
    }
    $('.contact-form form input[type=submit],.contact-form-box fieldset button[type=submit]').click(function (evt) {
        if ($('.gdpr_hook.contact').length > 0 && !$('.gdpr_hook.contact .gdpr_accept').is(':checked')) {
            evt.preventDefault();
            $(this).prop('disabled', true);
            return false;
        } else if ($('.gdpr_hook.contact.clone .gdpr_accept').is(':checked')) {
            $(this).prop('disabled', false);
        }
    });

    //out_of_stock.
    ets_gdpr_func.outOfStock();

    //register.
    if ($('#customer-form, #account-creation_form').length > 0 && $('.gdpr_hook.register').length > 0) {
        $('#customer-form button[type=submit], #account-creation_form button[type=submit]').prop('disabled', true);
    } else if ($('.gdpr_hook.register').length > 0) {
        $('.gdpr_hook.register').hide();
    }
    //ajax.
    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings && typeof settings.url !== typeof undefined && typeof settings.data !== typeof undefined && typeof settings.data !== "object" && typeof xhr !== typeof undefined) {
            if (xhr.readyState === 4 && typeof productLink !== typeof undefined && productLink !== '' && settings.url.indexOf(productLink) !== -1 && settings.data.indexOf('ajax') !== -1 && settings.data.indexOf('quantity_wanted') !== -1) {
                ets_gdpr_func.outOfStock();
            } else if (settings.url.indexOf('mailalerts') !== -1 && settings.data.indexOf('customer_email') !== -1 && $('.js-mailalert button[type=submit]:visible').length <= 0 && $('#pa_mailalert_link:visible').length <= 0) {
                $('.gdpr_hook.receive.clone').detach();
            } else if (settings.data && settings.data.indexOf('authentication') !== -1) {
                setTimeout(function () {
                    $('#customer-form button[type=submit], #account-creation_form button[type=submit]').prop('disabled', true);
                }, 1500);
            }
        }
    });
    $(document).on('click', '#customer-form button[type=submit], #account-creation_form button[type=submit]', function (evt) {
        if ($('.gdpr_hook.register').length > 0 && !$('.gdpr_hook.register .gdpr_accept').is(':checked')) {
            evt.preventDefault();
            $(this).prop('disabled', true);
            return false;
        } else if ($('.gdpr_hook.login.register .gdpr_accept').is(':checked')) {
            $(this).prop('disabled', false);
        }
    });
    //login.
    if (($('#login-form').length > 0 || $('#login_form').length > 0) && $('.gdpr_hook.login').length > 0) {
        //17, 16
        $('#login-form .forgot-password, #login_form .lost_password').before($('.gdpr_hook.login').clone(true));
        if ($('#login-form .gdpr_hook.login, #login_form .gdpr_hook.login').length > 0) {
            $('#login-form .gdpr_hook.login, #login_form .gdpr_hook.login').addClass('clone');
            $('.gdpr_hook.login:not(.clone)').remove();
            $('#submit-login, #SubmitLogin').prop('disabled', true);
        }

    } else if ($('.gdpr_hook.login').length > 0) {
        $('.gdpr_hook.login').detach();
    }
    $('#submit-login, #SubmitLogin').click(function (evt) {
        if ($('#login-form .gdpr_hook.login, #login_form .gdpr_hook.login').length > 0 && !$('.gdpr_hook.login.clone .gdpr_accept').is(':checked')) {
            evt.preventDefault();
            $(this).prop('disabled', true);
            return false;
        } else if ($('.gdpr_hook.login.clone .gdpr_accept').is(':checked')) {
            $(this).prop('disabled', false);
        }
    });
    //common all position.
    $(document).on('click', '.gdpr_accept', function () {
        ets_gdpr_func.updateAccept($(this), $(this).is(':checked'));
    });
});
