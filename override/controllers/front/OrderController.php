<?php
class OrderController extends OrderControllerCore
{
    /*
    * module: eicaptcha
    * date: 2024-12-07 05:17:22
    * version: 2.5.0
    */
    public function postProcess()
    {
        if (
            Tools::isSubmit('submitCreate')
            && Module::isInstalled('eicaptcha')
            && Module::isEnabled('eicaptcha')
            && false === Module::getInstanceByName('eicaptcha')->hookActionCustomerRegisterSubmitCaptcha([])
            && !empty($this->errors)
        ) {
            unset($_POST['submitCreate']);
        }
        parent::postProcess();
    }
}
