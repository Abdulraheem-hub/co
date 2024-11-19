<?php
class CustomerForm extends CustomerFormCore
{
    private $phone;
    public function fillWith(array $params = [])
    {
        if (isset($params['phone'])) {
            $this->phone = $params['phone'];
        }
        return parent::fillWith($params);
    }
    public function getTemplateVariables()
    {
        $variables = parent::getTemplateVariables();
        
        if (isset($this->phone)) {
            $variables['phone'] = $this->phone;
        }
        
        return $variables;
    } 
}