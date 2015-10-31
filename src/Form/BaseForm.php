<?php

namespace Evista\FormAPI\Form;

use Evista\FormAPI\ValueObject\FormField;

/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 14.
 * Time: 9:56
 */
abstract class BaseForm
{
    private $nonceKey = 'djlKJdlkjei877798a7lskdjf';
    private $nonceValue;
    private $submittedData;
    protected $formFields = [];

    public function __construct(){
        $this->templateVars = [];
        $this->nonceValue = $this->createNonce();
        $nonce = new FormField(FormField::TYPE_HIDDEN);
        $nonce
            ->setName('nonce')
            ->setValue($this->createNonce())
            ->setValidationCallback(function($value){
                if(function_exists('wp_verify_nonce')){
                    if(!wp_verify_nonce($value, $this->nonceKey)){
                        throw new \Exception('Unauthorized request');
                    }
                }

                // Use our csrf token
                else{
                    if (!isset($_SESSION['csrf_tokens'][$value])) {
                        throw new \Exception('Unauthorized request');
                    }
                    else{
                        unset($_SESSION['csrf_tokens'][$value]);
                    }
                }

                return false;
            })
            ->setMandatory(true);
        $key = 'nonce';
        $this->setFields($key, $nonce);

        // Set up form class (after submitting we need to know what class to initialize
        $classSelf = new \ReflectionClass($this);
        $name = $classSelf->getName();


        // Set template variable also
        $this->templateVars['form_fields']['nonce'] = $nonce;

        // Setup submission
        if(null !== $_POST){
            // If ajax, check formData parameter
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $keyValuePairs = explode('&', $_POST['formData']);
                array_walk($keyValuePairs, function($value){
                   list($key, $postValue) = explode('=', $value);
                   $this->submittedData[$key] = urldecode($postValue);
                });
            }
            else{
                $this->submittedData = $_POST;
            }
        }

        // Add child fields
        $this->generateFields();

    }

    /**
     * This is where a child have to define it's fields
     * @return mixed
     */
    abstract function generateFields();

    /**
     * Create nonce
     * @return string
     */
    private function createNonce(){
        if(function_exists('wp_create_nonce'))
            return wp_create_nonce($this->nonceKey);

        $nonce = md5(microtime(true).$this->nonceKey);
        if (empty($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = array();
        }
        $_SESSION['csrf_tokens'][$nonce] = true;
        return $nonce;
    }

    /**
     * populates form from POST after submission
     */

    public function populateFields(){
        if(count($this->submittedData)<1) return;
        array_map(function(FormField &$field){
            if(isset($this->submittedData[$field->getName()])){
                $raw = $this->submittedData[$field->getName()];

                $sanitized = $field->sanitize($raw);
                $field->setValue($sanitized);
            }else{
                // Unset value (see: checkboxes where value only sent when checkbox was checked
                if($field->getType() == FormField::TYPE_CHECKBOX){
                    $field->setValue(null);
                }

            }
        },
        $this->getFields());
    }

    /**
     * Validate form input
     * @return mixed
     */
    public function validate(){
        $errors = [];
        array_map(function(FormField $field) use (&$errors){
            if(isset($this->submittedData[$field->getName()])){
                // is it mandatory and empty?
                if($field->isMandatory() && strlen($this->submittedData[$field->getName()]) < 1){
                    $errors[$field->getName()] = [
                        'field' => $field->getName(),
                        'error' => "This is mandatory.",
                    ];

                    // Go to the next field, no need to validate
                    return true;
                }


                $validationResult = $field->validate();
                if($validationResult){
                    $errors[$field->getName()] =
                        [
                            'field' => $field->getName(),
                            'error' => $validationResult
                        ];
                }
                return false;
            }
        },
        $this->getFields());

        // Write to the template vars
        $this->addToTemplateVars($errors, 'form_errors');

        return $errors;
    }

    /**
     * @return mixed
     */
    public function getSubmittedData()
    {
        return $this->submittedData;
    }

    public function getTemplate()
    {
        return self::TEMPLATE_NAME;
    }

    public function getTemplateVars(){
        return $this->templateVars;
    }

    public function setTemplateVars(Array $templateVars)
    {
        $this->templateVars = $templateVars;

        return $this;
    }

    public function addToTemplateVars($element, $key = null)
    {
        $this->templateVars[$key] = $element;

        return $this;
    }


    public function getFields()
    {
        return $this->formFields;
    }

    public function setFields($key, $field)
    {
        $this->formFields[$key] = $field;

        return $this;
    }

}