
<?php
class Customer extends CustomerCore
{
    public $phone;

    public function __construct($id = null)
    {
        self::$definition['fields']['phone'] = [
            'type' => self::TYPE_STRING,
            'size' => 32
        ];
        
        parent::__construct($id);
    }
    
} 
