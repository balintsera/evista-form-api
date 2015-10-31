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
        // Assertion for submit field
        $this->assertInstanceOf('Evista\FormAPI\ValueObject\FormField', $form->getField('submit'));
        $this->assertEquals('Elküldöm', $form->getField('submit')->getValue());
        $this->assertEquals('submit', $form->getField('submit')->getType());
    }

    public function testCSRF(){
        $form = new ExampleForm();

        $this->assertNotEmpty($form->getField('nonce')->getValue());

    }

    public function testClassName(){
        $form = new ExampleForm();

        $this->assertEquals('ExampleForm', $form->getField('class')->getValue());
    }

}