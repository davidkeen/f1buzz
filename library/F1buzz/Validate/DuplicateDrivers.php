<?php

class F1buzz_Validate_DuplicateDrivers extends Zend_Validate_Abstract
{
    const DUPLICATE_DRIVERS = 'duplicateDrivers';

    protected $_messageTemplates = array(
        self::DUPLICATE_DRIVERS => 'Must select different drivers for 1st, 2nd and 3rd'
    );

    private $fieldsToCheck;

    /**
     * Constructor of this validator
     *
     * The argument to this constructor is the third argument to the elements' addValidator
     * method.
     *
     * @param array|string $fieldsToCheck
     */
    public function __construct($fieldsToCheck = array())
    {
        if (is_array($fieldsToCheck)) {
            foreach ($fieldsToCheck as $field) {
                $this->fieldsToCheck[] = (string) $field;
            }
        } else {
            $this->fieldsToCheck[] = (string) $fieldsToCheck;
        }
    }

    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);

        $error = false;

        foreach ($this->fieldsToCheck as $fieldName) {
            if (isset($context[$fieldName]) && $value == $context[$fieldName]) {
                $error = true;
                $this->_error(self::DUPLICATE_DRIVERS);
                break;
            }
        }

        return !$error;

    }
}
