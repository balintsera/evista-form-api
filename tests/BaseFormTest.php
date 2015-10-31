<?php

namespace Evista\FormAPI\Test;

use Evista\FormAPI\Form\ExampleForm;


/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 15.
 * Time: 12:13
 */
class BaseFormTest extends \PHPUnit_Framework_TestCase
{
    public function testTest(){
        $form = new ExampleForm();
        $fields = $form->getFields();
        // Assertion for submit field
        $this->assertInstanceOf('Evista\FormAPI\ValueObject\FormField', $fields['submit']);
        $this->assertEquals('Elküldöm', $fields['submit']->getValue());
        $this->assertEquals('submit', $fields['submit']->getType());
    }

}