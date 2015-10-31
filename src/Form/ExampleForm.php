<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 14.
 * Time: 10:01
 */

namespace Evista\FormAPI\Form;

use Evista\FormAPI\ValueObject\FormField;
use Evista\FormAPI\Form\BaseForm;


class ExampleForm extends BaseForm
{
    private $phoneNumberPattern = '/[^0-9,\+\s]/';

    /**
     * This is where form elements are defined
     */
    public function generateFields(){
        // Name field
        $name = new FormField(FormField::TYPE_TEXT_INPUT);
        $name
            ->setName('name')
            ->setAttributes(['placeholder' => 'Minta János', 'id' => 'name'])
            ->setMandatory(true);
        $this->formFields['name'] = $name;

        // Phone
        $phone = new FormField(FormField::TYPE_TEXT_INPUT);
        $phone
            ->setName('phone')
            ->setAttributes(['placeholder' => '+36 30 111 2222', 'id' => 'phone'])
            ->setSanitizationCallback(function($value){
                // only numbers, whitespaces and +
                return trim(preg_replace($this->phoneNumberPattern, '', $value));
            })
            ->setValidationCallback(
                function($value){
                    // Length constrain
                    if(strlen($value)<5){
                        return 'Telephone number is not valid';
                    }

                    // Regex constrain
                    if(preg_match($this->phoneNumberPattern, $value)){
                        return 'Telephone number is not valid';
                    }

                    // False means it's OK!
                    return false;
                }
            )
            ->setMandatory(true);
        $this->formFields['phone'] = $phone;

        // Email
        $email = new FormField(FormField::TYPE_TEXT_INPUT);
        $email
            ->setName('email')
            ->setAttributes(['placeholder' => 'minta.janos@info.hu', 'id' => 'email'])
            ->setMandatory(true)
            ->setSanitizationCallback(function($value){return sanitize_email($value);})
            ->setValidationCallback(
                function($value){
                    //var_dump('validating email: '.$value);
                    if(!is_email($value)){
                        return 'Email is not valid';
                    }

                    // False means it's OK!
                    return false;
                }
            );
        $this->formFields['email'] = $email;

        // Submit
        $submit = new FormField(FormField::TYPE_SUBMIT);
        $submit->setValue('Elküldöm');
        $this->formFields['submit'] = $submit;

    }

}