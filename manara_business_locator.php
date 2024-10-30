<?php

/*
  Plugin Name: Manara Business Locator
  Plugin URI: https://wordpress.org/plugins/manara-business-locator/
  Description: Hooks the <code>[manara_business_locator]</code> shortcode
  Author: Rodrigo Manara
  Author URI: http://rodrigomanara.co.uk
  Version: 1.0
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * set global 
 */

define('manara_business_locator_folder_path', "manara-business-locator");
define('manara_business_locator_path_pluggin', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . manara_business_locator_folder_path . DIRECTORY_SEPARATOR);
define('manara_business_locator_path_pluggin_path_view', manara_business_locator_path_pluggin . "Application" . DIRECTORY_SEPARATOR . "view");


/* * *********************************************************
 *  add composer *********
 * *************
 */
include_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . manara_business_locator_folder_path . DIRECTORY_SEPARATOR . 'vendor/autoload.php';


/** sessao administrativa * */
add_action('admin_menu', 'manara_business_locator_admin_menu');
function manara_business_locator_admin_menu() {
    add_menu_page('Business Locator', 'Business Locator', 'manage_categories', "\\" . manara_business_locator_folder_path . '/Application/admin/panel.php', '', plugins_url(manara_business_locator_folder_path .'/Application/public/images/look.png'), 6);
}

/** register css for menu admin panel **/
add_action('admin_enqueue_scripts', 'manara_business_locator_admin_style');
function manara_business_locator_admin_style() {

    $array[] = plugins_url(manara_business_locator_folder_path .'/Application/public/css/admin.css'); 
    for ($i = 0; $i < count($array); $i++) {
        wp_register_style('manara_business_locator_admin_style' . $i, $array[$i]);
    }

    for ($i = 0; $i < count($array); $i++) {
        wp_enqueue_style('manara_business_locator_admin_style' . $i);
    }
}

/** register script for menu admin panel **/
add_action('admin_enqueue_scripts', 'manara_business_locator_admin_script');
function manara_business_locator_admin_script() {

    
    $array[] = plugins_url(manara_business_locator_folder_path .'/Application/public/js/bootstrap.min.js'); 
    for ($i = 0; $i < count($array); $i++) {
        wp_register_script('manara_business_locator_admin_script' . $i, $array[$i]);
    }

    for ($i = 0; $i < count($array); $i++) {
        wp_enqueue_script('manara_business_locator_admin_script' . $i);
    }
}

/*** register public css **/
add_action('wp_enqueue_scripts', 'manara_business_locator_script');
function manara_business_locator_script() {
 
    $array[] = plugins_url(manara_business_locator_folder_path . '/Application/public/js/jquery.ui.map.js '); 
    for ($i = 0; $i < count($array); $i++) {
        wp_register_script('manara_business_locator_script' . $i, $array[$i]);
    }

    for ($i = 0; $i < count($array); $i++) {
        wp_enqueue_script('manara_business_locator_script' . $i);
    }
}

//allow redirection, even if my theme starts to send output to the browser
add_action('init', 'manara_business_locator_do_output_buffer');
function manara_business_locator_do_output_buffer() {
        ob_start();
}

add_action ('wp_loaded', 'manara_business_locator_redirect');
function manara_business_locator_redirect($redirect) {
    wp_redirect($redirect); 
}

/** register pluggin **/
$dbSetUp = new Manara\Business\locator\Application\lib\DBIntstall();
register_activation_hook(__FILE__ , array(&$dbSetUp , 'createDB'));
register_deactivation_hook(__FILE__ , array(&$dbSetUp , 'deleteDB'));


$add_shortcode = new Manara\Business\locator\Application\lib\display();
add_shortcode("manara_business_locator", array($add_shortcode, "getPanelDisplay"));