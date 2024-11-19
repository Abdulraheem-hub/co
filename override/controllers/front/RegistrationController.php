<?php
class RegistrationController extends RegistrationControllerCore
{
    /* public function initContent()
    {
        $register_form = $this
            ->makeCustomerForm()
            ->setGuestAllowed(false)
            ->fillWith(Tools::getAllValues());

        // If registration form was submitted
        if (Tools::isSubmit('submitCreate')) {
            $hookResult = array_reduce(
                Hook::exec('actionSubmitAccountBefore', [], null, true),
                function ($carry, $item) {
                    return $carry && $item;
                },
                true
            );

            // If no problem occurred in the hook, process the registration
            if ($hookResult && $register_form->submit() && !$this->ajax) {
                try {
                    // Get the newly created customer email
                    $email = Tools::getValue('email');
                    
                    // Get customer by email
                    $customer = new Customer();
                    $authentication = $customer->getByEmail($email);
                    
                    if (Validate::isLoadedObject($authentication)) {
                        // Set up the customer session
                        $this->context->customer = $authentication;
                        $this->context->cookie->id_customer = (int)$authentication->id;
                        $this->context->cookie->customer_lastname = $authentication->lastname;
                        $this->context->cookie->customer_firstname = $authentication->firstname;
                        $this->context->cookie->logged = 1;
                        $this->context->cookie->check_cgv = 1;
                        $this->context->cookie->secure_key = $authentication->secure_key;
                        $this->context->cookie->is_guest = $authentication->is_guest;
                        $this->context->cookie->passwd = $authentication->passwd;
                        $this->context->cookie->email = $authentication->email;
                        
                        // Write the cookie
                        $this->context->cookie->write();
                        
                        // Execute hooks
                        Hook::exec('actionAuthentication', ['customer' => $authentication]);
                        
                        // Log the success
                        PrestaShopLogger::addLog(
                            'Customer authentication success: ' . $authentication->id,
                            1,
                            null,
                            'Customer',
                            (int)$authentication->id,
                            true
                        );
                        
                        // Redirect to my-account
                        Tools::redirect(__PS_BASE_URI__);
                    } else {
                        PrestaShopLogger::addLog(
                            'Customer authentication failed: Invalid customer object',
                            3
                        );
                    }
                } catch (Exception $e) {
                    PrestaShopLogger::addLog(
                        'Registration error: ' . $e->getMessage(),
                        3
                    );
                }
            }
        }

        $this->context->smarty->assign([
            'register_form' => $register_form->getProxy(),
            'hook_create_account_top' => Hook::exec('displayCustomerAccountFormTop'),
        ]);
        $this->setTemplate('customer/registration');

        parent::initContent();
    } */
}