<?php
namespace Evista\FormAPI;

use Evista\CrmIntegration\Form\ExampleForm;

include_once('vendor/autoload.php');


/*
  Plugin Name: Evista Form API
  Plugin URI:
  Description: Object Oriented Form api plugin for WordPress
  Version: 1.0
  Author: Balint Sera
  Author URI: http://evista-agency.com
  */


class EvistaFormAPI{
    public function __construct(){
        // Inicializations goes here
    }

    /**
     * Example form, try it in a template file: $evistaFormAPI->exampleForm();
     */
    public function exampleForm(){
        $form = new ExampleForm();
        // Populate field
        $form->populateFields();

        // Validation
        if(count($errors = $form->validate())>0){
            //var_dump($errors);
            // var_dump("errors");
        }

        // When posted, redirect to some other page
        if(isset($_POST['nonce'])){
            echo 'All right';
        }


        // Render form
        $templateFile = $form->getTemplate();
        \Timber::render($templateFile, $form->getTemplateVars());
    }
}

$evistaFormAPI = new EvistaFormAPI();
?>