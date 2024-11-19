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
var gdpr_func = {
    checkAlls: function (select, item) {
        if (select.length > 0 && item.length > 0) {
            if (select.is(':checked')) {
                if (typeof select.attr('onclick') == "undefined")
                    item.prop('checked', true);
            } else {
                if (typeof select.attr('onclick') == "undefined")
                    item.prop('checked', false);
            }
        }
    },
    changeTemplate: function () {
        if ($('#ETS_GDPR_WELCOME_TEMPLATE_bottom').length > 0) {
            if ($('#ETS_GDPR_WELCOME_TEMPLATE_bottom, #ETS_GDPR_WELCOME_TEMPLATE_top').is(':checked')) {
                $('.row_ets_gdpr_welcome_box_width').hide();
            } else {
                $('.row_ets_gdpr_welcome_box_width').show();
            }
        }
    },
    changeDelData: function () {
        if ($('#ETS_GDPR_EMAIL_WHEN_DELETE_on').is(':checked')) {
            $('.row_ets_gdpr_deletion_subject').show();
            $('.row_ets_gdpr_deletion_email').show();
        } else {
            $('.row_ets_gdpr_deletion_subject').hide();
            $('.row_ets_gdpr_deletion_email').hide();
        }
    },
    changeDeclineDel: function () {
        if ($('#ETS_GDPR_EMAIL_WHEN_DECLINE_on').is(':checked')) {
            $('.row_ets_gdpr_refusal_subject, .row_ets_gdpr_refusal_email').show();
        } else {
            $('.row_ets_gdpr_refusal_subject, .row_ets_gdpr_refusal_email').hide();
        }
    },
    changeDataView: function () {
        if ($('#ETS_GDPR_ALLOW_VIEW_on').length > 0 && $('.row_ets_gdpr_data_to_view').length > 0 && $('#ETS_GDPR_ALLOW_VIEW_on').is(':checked')) {
            $('.row_ets_gdpr_data_to_view, .row_ets_gdpr_allow_download').show();
        } else {
            $('.row_ets_gdpr_data_to_view, .row_ets_gdpr_allow_download').hide();
        }
    },
    closeForm: function () {
        if ($('#send_form').length > 0 && $('#send_form').hasClass('actived'))
            $('#send_form').removeClass('actived');
    },
    changeAsk: function () {
        if ($('.row_ets_gdpr_email_when_require.rel').length > 0) {
            $('#ETS_GDPR_EMAIL_WHEN_DECLINE_on').prop('checked', true);
            $('#ETS_GDPR_EMAIL_WHEN_DECLINE_off').prop('checked', false);
            $('.row_ets_gdpr_email_when_decline, .row_ets_gdpr_email_when_require').show();
        } else {
            $('#ETS_GDPR_EMAIL_WHEN_DECLINE_on').prop('checked', false);
            $('#ETS_GDPR_EMAIL_WHEN_DECLINE_off').prop('checked', true);
            $('.row_ets_gdpr_email_when_decline, .row_ets_gdpr_email_when_require').hide();
        }
        gdpr_func.changeDeclineDel();
    },
    changeNumberColor: function () {
        if ($('#ETS_GDPR_NUMBER_NOTICES_on').length > 0 && $('#ETS_GDPR_NUMBER_NOTICES_on').is(':checked')) {
            $('.row_ets_gdpr_number_bg_color, .row_ets_gdpr_number_text_color').show();
        } else {
            $('.row_ets_gdpr_number_bg_color, .row_ets_gdpr_number_text_color').hide();
        }
    },
    changePolicy: function () {
        if ($('#ETS_GDPR_POLICY_ENABLED_on').length > 0 && $('#ETS_GDPR_POLICY_ENABLED_on').is(':checked')) {
            $('.row_ets_gdpr_policy_btn_title, .row_ets_gdpr_policy_by_popup, .row_ets_gdpr_policy_page_url').show();
            gdpr_func.usePolicyPopup();
        } else {
            $('.row_ets_gdpr_policy_btn_title, .row_ets_gdpr_policy_by_popup, .row_ets_gdpr_policy_page_url').hide();
        }
    },
    usePolicyPopup: function () {
        if ($('#ETS_GDPR_POLICY_BY_POPUP_on').length > 0 && $('#ETS_GDPR_POLICY_BY_POPUP_on').is(':checked')) {
            $('.row_ets_gdpr_policy_page_url').hide();
        } else {
            $('.row_ets_gdpr_policy_page_url').show();
        }
    },
    delPersonData: function () {
        if ($('#ETS_GDPR_ALLOW_DELETE_on').length > 0 && $('#ETS_GDPR_ALLOW_DELETE_on').is(':checked')) {
            $('.row_ets_gdpr_can_delete').show();
        } else {
            $('.row_ets_gdpr_can_delete').hide();
        }
    }
}

$(document).ready(function () {
    gdpr_func.changeTemplate();
    gdpr_func.changeDataView();
    gdpr_func.changeAsk();
    gdpr_func.changeNumberColor();
    gdpr_func.changePolicy();
    gdpr_func.delPersonData();
    /*config*/
    if ($('#form-ets_gdpr_deletion').length > 0 && $('#form-ets_gdpr_deletion > .panel > div.row > div.col-lg-6').length > 0 && $('#table-ets_gdpr_deletion tbody tr').length > 0 && $('.gdpr-link-export').length > 0) {
        $('#form-ets_gdpr_deletion > .panel > div.row > div.col-lg-6').append($('.gdpr-link-export').clone().addClass('clone'));
        if ($('#form-ets_gdpr_deletion > .panel > div.row > div.col-lg-6 .gdpr-link-export.clone').length > 0) {
            $('#form-ets_gdpr_deletion > .panel > div.row > div.col-lg-6 .gdpr-link-export.clone').removeClass('hidden');
            $('.gdpr-link-export:not(.clone)').detach();
        }
    }
    if ($('.panel-heading > .badge').length > 0 && parseInt($('.panel-heading > .badge').html().trim()) == 0) {
        $('.panel-heading > .badge').hide();
    }
    $('input[id^=ETS_GDPR_ALLOW_DELETE]').change(function () {
        gdpr_func.delPersonData();
    });
    $('input[id^=ETS_GDPR_POLICY_ENABLED]').change(function () {
        gdpr_func.changePolicy();
    });
    $('input[id^=ETS_GDPR_POLICY_BY_POPUP]').change(function () {
        gdpr_func.usePolicyPopup();
    });
    $('#ETS_GDPR_MESSAGE_BG_OPACITY').change(function () {
        var $val = 0.8;
        if ($(this).val() == 10 || $(this).val() == 0)
            $val = parseInt($(this).val()) ? 1 : 0;
        else
            $val = parseFloat(parseInt($(this).val()) / 10).toFixed(1);

        $('#ETS_GDPR_MESSAGE_BG_OPACITY_VAL').text($val);
    });
    if ($('#ETS_GDPR_EMAIL_WHEN_DELETE_on').length > 0) {
        gdpr_func.changeDelData();
    }
    $('input[name=ETS_GDPR_EMAIL_WHEN_DELETE]').change(function () {
        gdpr_func.changeDelData();
    });
    if ($('#ETS_GDPR_EMAIL_WHEN_DECLINE_on').length > 0) {
        gdpr_func.changeDeclineDel();
    }
    $('input[name=ETS_GDPR_EMAIL_WHEN_DECLINE]').change(function () {
        gdpr_func.changeDeclineDel();
    });
    $('input[name=ETS_GDPR_WELCOME_TEMPLATE]').change(function () {
        gdpr_func.changeTemplate();
    });
    $('input[name=ETS_GDPR_ALLOW_VIEW]').change(function () {
        gdpr_func.changeDataView();
    });
    $('input[name=ETS_GDPR_NUMBER_NOTICES]').change(function () {
        gdpr_func.changeNumberColor();
    });
    if ($('#checkme[value=ALL]').length > 0) {
        $('#checkme[value=ALL]').click(function () {
            gdpr_func.checkAlls($(this), $('input[id^=ETS_GDPR_GROUP_TO_SEE], input[id^=display_to]'));
        });
        $('input[id^=ETS_GDPR_GROUP_TO_SEE], input[id^=display_to]').click(function () {
            if ($('input[id^=ETS_GDPR_GROUP_TO_SEE]:checked, input[id^=display_to]:checked').length === $('input[id^=ETS_GDPR_GROUP_TO_SEE], input[id^=display_to]').length) {
                $('#checkme').click();
            }
        });
    }
    if ($('#ETS_GDPR_DATA_TO_VIEW_ALL').length > 0) {
        $('#ETS_GDPR_DATA_TO_VIEW_ALL').click(function () {
            gdpr_func.checkAlls($(this), $('input[id^=ETS_GDPR_DATA_TO_VIEW]:not([id$=ALL])'));
        });
        $('input[id^=ETS_GDPR_DATA_TO_VIEW]:not([id$=ALL])').click(function () {
            if ($('input[id^=ETS_GDPR_DATA_TO_VIEW]:not([id$=ALL]):checked').length === $('input[id^=ETS_GDPR_DATA_TO_VIEW]:not([id$=ALL])').length) {
                $('#ETS_GDPR_DATA_TO_VIEW_ALL').click();
            } else
                $('input[id^=ETS_GDPR_DATA_TO_VIEW_ALL]').prop('checked', false);
        });
    }
    if ($('#ETS_GDPR_CAN_DELETE_ALL').length > 0) {
        $('#ETS_GDPR_CAN_DELETE_ALL').click(function () {
            gdpr_func.checkAlls($(this), $('input[id^=ETS_GDPR_CAN_DELETE]:not([id$=ALL])'));
        });
        $('input[id^=ETS_GDPR_CAN_DELETE]:not([id$=ALL])').click(function () {
            if ($('input[id^=ETS_GDPR_CAN_DELETE]:not([id$=ALL]):checked').length === $('input[id^=ETS_GDPR_CAN_DELETE]:not([id$=ALL])').length) {
                $('#ETS_GDPR_CAN_DELETE_ALL').click();
            } else
                $('input[id^=ETS_GDPR_CAN_DELETE_ALL]').prop('checked', false);
        });
    }
    if ($('#ETS_GDPR_NOTIFY_WHEN_ALL').length > 0) {
        $('#ETS_GDPR_NOTIFY_WHEN_ALL').click(function () {
            gdpr_func.checkAlls($(this), $('input[id^=ETS_GDPR_NOTIFY_WHEN]:not([id$=ALL])'));
        });
        $('input[id^=ETS_GDPR_NOTIFY_WHEN]:not([id$=ALL])').click(function () {
            if ($('input[id^=ETS_GDPR_NOTIFY_WHEN]:not([id$=ALL]):checked').length === $('input[id^=ETS_GDPR_NOTIFY_WHEN]:not([id$=ALL])').length) {
                $('#ETS_GDPR_NOTIFY_WHEN_ALL').click();
            }
        });
    }
    if ($('select[name="ETS_GDPR_DISCOUNT_NETWORKS[]"] option[value="all"]').is(':selected'))
        $('select[name="ETS_GDPR_DISCOUNT_NETWORKS[]"] option').prop('selected', true);
    $('select[name="ETS_GDPR_DISCOUNT_NETWORKS[]"] option').click(function () {
        if ($(this).val() === 'all' && !$('select[name="ETS_GDPR_DISCOUNT_NETWORKS[]"][value="all"]').is(':selected'))
            $('select[name="ETS_GDPR_DISCOUNT_NETWORKS[]"] option').prop('selected', true);
    });
    /*end config*/
    $('.gdpr_action_deletion .gdpr_action_item.envelope').click(function (e) {
        e.preventDefault();
        if ($('.gdpr_admin_content #send_form').length > 0) {
            var $tr = $(this).parents('tr');
            var $title = $(this).find('a.btn-default').html();
            $('.gdpr_admin_content #send_form').addClass('actived');
            $('#id_customer').val($(this).data('id'));
            $('#send_form').find('input:not("#id_customer"), textarea').val('');
            if ($('#send_form .panel-heading').length > 0 && $tr.find('td.gdpr-customer-name-form').length > 0 && $tr.find('td.gdpr-email-form').length > 0)
                $('#send_form .panel-heading').html($title + ': ' + $tr.find('td.gdpr-customer-name-form').text() + ' (' + $tr.find('td.gdpr-email-form').text() + ') ');
            if ($('#send_form').find('.bootstrap').length > 0)
                $('#send_form').find('.bootstrap').remove();
        }
    });
    $('.gdpr_close_form, .gdpr_btn_cancel').click(function () {
        gdpr_func.closeForm();
        return false;
    });
    $(document).keyup(function (e) {
        if (e.keyCode === 27) {
            gdpr_func.closeForm();
        }
    });
    $(document).mouseup(function (e) {
        var popup = $('#send_form #fieldset_0');
        if (!popup.is(e.target) && popup.has(e.target).length === 0)
            gdpr_func.closeForm();
    });
    $('#send_form_submit_btn').click(function (e) {
        e.preventDefault();
        var obj = $(this);
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            var formData = new FormData(obj.parents('form').get(0));
            if ($('#send_form').find('.bootstrap').length > 0)
                $('#send_form').find('.bootstrap').remove();
            $.ajax({
                url: postLink,
                data: formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (json) {
                    if (json == null)
                        return false;
                    obj.removeClass('active');
                    if (!json.errors) {
                        showSuccessMessage(successLabel);
                        gdpr_func.closeForm();
                    } else
                        obj.parent('.panel-footer').before(json.errors);
                },
                error: function (xhr, status, error) {
                    obj.removeClass('active');
                }
            });
        }
        return false;
    });
    $(document).on('click', '.gdpr-approve > a, .gdpr-declined > a', function (e) {
        e.preventDefault();
        var obj = $(this);
        var $comfirm = $(this).parent('li').hasClass('gdpr-approve') ? gdpr_confirm_approve : gdpr_confirm_declined;
        if (!$(this).hasClass('active') && confirm($comfirm)) {
            $(this).addClass('active');
            $.ajax({
                url: obj.attr('href'),
                type: 'post',
                dataType: 'json',
                data: {ajax: true},
                success: function (json) {
                    obj.removeClass('active');
                    if (json == null)
                        return false;
                    if (!json.errors) {
                        if (json.success)
                            showSuccessMessage(json.success);
                        //pending
                        if (json.nb_pending) {
                            if ($('.gdpr_sub_pending span.gdpr-total').length > 0) {
                                $('.gdpr_sub_pending span.gdpr-total').html(json.nb_pending);
                            } else if ($('.gdpr_sub_pending').length > 0) {
                                $('.gdpr_sub_pending').append('<span class="gdpr-total sub-tab badge badge-default">' + json.nb_pending + '</span>');
                            }
                        } else {
                            if ($('.gdpr_sub_pending span.gdpr-total').length > 0) {
                                $('.gdpr_sub_pending span.gdpr-total').detach();
                            }
                        }
                        //approved
                        if (json.nb_approved) {
                            if ($('.gdpr_sub_approved span.gdpr-total').length > 0) {
                                $('.gdpr_sub_approved span.gdpr-total').html(json.nb_approved);
                            } else if ($('.gdpr_sub_approved').length > 0) {
                                $('.gdpr_sub_approved').append('<span class="gdpr-total sub-tab badge badge-default">' + json.nb_approved + '</span>');
                            }
                        } else {
                            if ($('.gdpr_sub_approved span.gdpr-total').length > 0) {
                                $('.gdpr_sub_approved span.gdpr-total').detach();
                            }
                        }
                        //declined
                        if (json.nb_declined) {
                            if ($('.gdpr_sub_declined span.gdpr-total').length > 0) {
                                $('.gdpr_sub_declined span.gdpr-total').html(json.nb_declined);
                            } else if ($('.gdpr_sub_declined').length > 0) {
                                $('.gdpr_sub_declined').append('<span class="gdpr-total sub-tab badge badge-default">' + json.nb_declined + '</span>');
                            }
                        } else {
                            if ($('.gdpr_sub_declined span.gdpr-total').length > 0) {
                                $('.gdpr_sub_declined span.gdpr-total').detach();
                            }
                        }
                        //declined
                        if (json.nb_withdraw) {
                            if ($('.gdpr_sub_withdraw span.gdpr-total').length > 0) {
                                $('.gdpr_sub_withdraw span.gdpr-total').html(json.nb_withdraw);
                            } else if ($('.gdpr_sub_withdraw').length > 0) {
                                $('.gdpr_sub_withdraw').append('<span class="gdpr-total sub-tab badge badge-default">' + json.nb_withdraw + '</span>');
                            }
                        } else {
                            if ($('.gdpr_sub_withdraw span.gdpr-total').length > 0) {
                                $('.gdpr_sub_withdraw span.gdpr-total').detach();
                            }
                        }
                        if (obj.parents('tr').eq(0).length > 0) {
                            obj.parents('tr').eq(0).remove();
                        }
                        if ($('#table-ets_gdpr_deletion tbody tr').length <= 0) {
                            $('.gdpr-link-export').addClass('hidden');
                            var tr = '<tr>\n' +
                                '\t\t<td class="list-empty" colspan="5">\n' +
                                '\t\t\t<div class="list-empty-msg">\n' +
                                '\t\t\t\t<i class="icon-warning-sign list-empty-icon"></i>\n' +
                                '\t\t\t\t' + gdpr_no_records_found + '\n' +
                                '\t\t\t</div>\n' +
                                '\t\t</td>\n' +
                                '\t</tr>';
                            $('#table-ets_gdpr_deletion tbody').append(tr);
                        }
                    } else
                        showErrorMessage(json.errors);
                },
                error: function (xhr, status, error) {
                    obj.removeClass('active');
                }
            });
        }
        return false;
    });
    $(document).on('click', 'a.gdpr-declined, a.gdpr-approve', function () {
        var $comfirm = $(this).hasClass('gdpr-approve') ? gdpr_confirm_approve : gdpr_confirm_declined;
        if (confirm($comfirm))
            return true;
        return false;
    });
    if (typeof gdpr_chart !== "undefined") {
        var labelX = gdpr_x_days, labelY = gdpr_y_label;
        if ($('#months').length > 0 && $('#months').val() == '' && $('#years').length > 0 && $('#years').val() != '')
            labelX = gdpr_x_months;
        else if ($('#years').length > 0 && $('#years').val() == '')
            labelX = gdpr_x_years;
        nv.addGraph(function () {
            var line_chart = nv.models.lineChart()
                .useInteractiveGuideline(true)
                .x(function (d) {
                    return (d !== undefined ? d[0] : 0);
                })
                .y(function (d) {
                    return (d !== undefined ? parseInt(d[1]) : 0);
                })
                .margin({left: 80})
                .showLegend(true)
                .showYAxis(true)
                .showXAxis(true);
            line_chart.xAxis
                .axisLabel(labelX)
                .tickFormat(d3.format('d'));
            line_chart.yAxis
                .axisLabel(labelY)
                .tickFormat(d3.format('d'));
            d3.select('.gdpr_admin_chart svg')
                .datum(gdpr_chart)
                .transition().duration(500)
                .call(line_chart);
            nv.utils.windowResize(line_chart.update);
            return line_chart;
        });

    }
});