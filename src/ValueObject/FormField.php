<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 14.
 * Time: 10:21
 */

namespace Evista\FormAPI\ValueObject;


class FormField
{
    const TYPE_TEXT_INPUT = 'input';
    const TYPE_SUBMIT = 'submit' ;
    const TYPE_HIDDEN = 'hidden';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT = 'select';

    private $type;
    private $attributes = [];
    private $name;
    private $value;
    private $default;
    private $tagName = 'input';
    private $mandatory = FALSE;
    private $sanitizationCallback;
    private $validationCallback;
    private $label; // only checkboxes self::TYPE_CHECKBOX

    public function __construct($type){
        $this->type = $type;

        // Default callbacks do nothing, just returns the original
        $this->sanitizationCallback = function($value){ return $value; };
        $this->validationCallback = function($value){ return false; };

        switch($this->type){
            case self::TYPE_TEXTAREA:
                $this->tagName = 'textarea';
                break;
            case self::TYPE_SELECT:
                $this->tagName = 'select';
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return FormField
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return FormField
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return FormField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return FormField
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     * @return FormField
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMandatory()
    {
        return $this->mandatory;
    }

    /**
     * @param boolean $mandatory
     * @return FormField
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * @param $inputValue
     * @return closure's return value
     */
    public function getSanitizationCallback($inputValue)
    {
        return $this->sanitizationCallback;
    }

    /**
     * @param \Closure $sanitizationCallback
     * @return FormField
     */
    public function setSanitizationCallback(\Closure $sanitizationCallback)
    {
        $this->sanitizationCallback = $sanitizationCallback;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return FormField
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @param string $tagName
     * @return FormField
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }




    public function sanitize($inputValue){
        $function = $this->sanitizationCallback;
        return $function($inputValue);
    }

    /**
     * @return mixed
     */
    public function getValidationCallback()
    {
        return $this->validationCallback;
    }

    /**
     * @param mixed $validationCallback
     * @return FormField
     */
    public function setValidationCallback($validationCallback)
    {
        $this->validationCallback = $validationCallback;

        return $this;
    }


    public function validate(){
        $validateFunction = $this->validationCallback;
        return $validateFunction($this->getValue());
    }



}
