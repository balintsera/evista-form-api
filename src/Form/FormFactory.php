<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 14.
 * Time: 9:59
 */

namespace Evista\FormAPI\Form;


class FormFactory
{
    const CRM_FORM_SHORT = 'short';
    const CRM_FORM_LONG = 'long';

    public static function create($type){
        switch($type){
            case self::CRM_FORM_LONG:
                return new LongForm();

            case self::CRM_FORM_SHORT:
                return new ShortForm();
        }
    }

}