<?php

/*

Plugin Name:Braintree Donations

Plugin URI: http://braintreedonations.fasterthemes.com/

Description:  The Braintree Donations plugin allows websites to accept one time or recurring donations using Braintree payment Gateway.

Version: 1.0

Author: FasterThemes

Author URI: http://fasterthemes.com/

*/

$siteurl = get_option('siteurl');

define('BRAINTREE_FOLDER', dirname(plugin_basename(__FILE__)));

define('BRAINTREE_URL', plugin_dir_url( __FILE__ ) );

define('BRAINTREE_FILE_PATH', dirname(__FILE__));

define('BRAINTREE_DIR_NAME', basename(BRAINTREE_FILE_PATH));

/* css call*/

function braintree_scripts() {

   wp_enqueue_style('braintree-custom',BRAINTREE_URL . 'css/bootstrap.css',array());   

    }

add_action( 'wp_enqueue_scripts', 'braintree_scripts' );

 function load_admin_style() {

         wp_enqueue_style( 'admin_css', BRAINTREE_URL . 'css/mycustomestyle.css', false );
         
       }

 add_action( 'admin_enqueue_scripts', 'load_admin_style' );

/*admin menu*/

add_action('admin_menu','braintree_admin_menu');

function braintree_admin_menu()

{  

add_menu_page("braintree","braintree Settings",8,__FILE__,"braintree_settings"); 

}

/*pages*/

function braintree_settings(){

	 include_once(BRAINTREE_FILE_PATH.'/settings.php');

}

/*shoertcode*/

function braintree(){

 include_once(BRAINTREE_FILE_PATH.'/shortcode.php');	

} 

add_shortcode( 'BrainTree', 'braintree' );

?>