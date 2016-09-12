<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

$AddLibraryPath = 'e:\www\libraries';
set_include_path(get_include_path() . PATH_SEPARATOR . $AddLibraryPath);

//echo PATH_SEPARATOR.'<br>';
//echo $AddLibraryPath.'<br>';
//echo get_include_path().'<br>';

require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_View');
Zend_Loader::loadClass('Zend_Form');

$view = new Zend_View();

$form = new Zend_Form;
$form->setAction('/user/login')->setMethod('post');

// Create and configure username element:$username =
$form->createElement('text', 'username');
$username = new Zend_Form_Element_Text('username');
$username   ->addValidator('alnum')
            ->addValidator('regex', false, array('/^[a-z]+/'))
            ->addValidator('stringLength', false, array(6, 20))
            ->setRequired(true)
            ->addFilter('StringToLower');
// Create and configure password element:$password =
$form->createElement('password', 'password');
$password = new Zend_Form_Element_Password('password');
$password   ->addValidator('StringLength', false, array(6))
            ->setRequired(true);
// Add elements to form:
$form       ->addElement($username)
            ->addElement($password)
// use addElement() as a factory to create 'Login' button:
            ->addElement('submit', 'login', array('label' => 'Login'));
            
?><h2>Please login:</h2><?php
echo $form->render($view);


?>