<?php
  /*
  Plugin Name: Simple BMI Form
  Plugin URI:  http://www.topcode.co.uk/developments/simple-bmi-form/
  Description: Simple BMI calculator form. The visitor may select Imperial, US or Metric units.
  Version:     1.0.13
  Author:      lorro
  Author URI:  http://www.topcode.co.uk/
  License:     GPL3
  License URI: https://www.gnu.org/licenses/gpl-3.0.html
  Domain Path: /languages/
  Text Domain: simple-bmi-form
  */

  $sbf_version = '1.0.13';

  // turn on warnings and notices for easier debugging
  // ini_set('display_errors', 1);
  // error_reporting( E_ALL ^ E_STRICT );

  defined( 'ABSPATH' ) or die( 'Direct access is not permitted' );

  define( 'SBF_PATH', plugin_dir_path( __FILE__ ) );
  // eg: SBF_PATH = '/home/user/public_html/domain/wp-content/plugins/simple-bmi-form/';

  define( 'SBF_URL', plugin_dir_url( __FILE__ ) );
  // eg: SBF_URL = 'http://www.domain.co.uk/wp-content/plugins/simple-bmi-form/';

  // load translations
  add_action( 'init', 'sbf_init' );
  function sbf_init() {
    // load_plugin_textdomain( $domain, $abs_rel_path__DEPRECATED, $plugin_rel_path );
    load_plugin_textdomain( 'simple-bmi-form', false,  SBF_PATH.'languages' );
  }

  // register scripts
  add_action( 'wp_loaded', 'sbf_register_scripts' );
  function sbf_register_scripts() {
    global $sbf_version;
    // wp_register_script( $handle, $src, $deps = array(), $ver, $in_footer );
    // $src = full url or path relative to wordpress root
    wp_register_script( 'sbf_admin_js', SBF_URL.'js/admin.js', array( 'wp-color-picker' ), $sbf_version, true );
    wp_register_script( 'sbf_public_js', SBF_URL.'js/public.js', array( 'jquery' ), $sbf_version, true );
    // localize the script with translations
    $translation_array = array(
      'stones' => __( 'stones', 'simple-bmi-form' ),
      'pounds' => __( 'pounds', 'simple-bmi-form' ),
      'feet' => __( 'feet', 'simple-bmi-form' ),
      'inches' => __( 'inches', 'simple-bmi-form' ),
      'kilograms' => __( 'kilograms', 'simple-bmi-form' ),
      'metres' => __( 'metres', 'simple-bmi-form' ),
      'very_severly_underweight' => __( 'You are "Very severely underweight."', 'simple-bmi-form' ),
      'severly_underweight' => __( 'You are "Severely underweight."', 'simple-bmi-form' ),
      'underweight' => __( 'You are "Underweight."', 'simple-bmi-form' ),
      'normal' => __( 'Your weight is "Normal."', 'simple-bmi-form' ),
      'overweight' => __( 'You are "Overweight."', 'simple-bmi-form' ),
      'obese_I' => __( 'You are "Obese Class I."', 'simple-bmi-form' ),
      'obese_II' => __( 'You are "Obese Class II."', 'simple-bmi-form' ),
      'obese_III' => __( 'You are "Obese Class III."', 'simple-bmi-form' ),
      'enter_stones' => __( 'Enter a value for stones.', 'simple-bmi-form' ),
      'pounds_min' => __( 'Pounds must be less than 14.', 'simple-bmi-form' ),
      'enter_pounds' => __( 'Enter a value for pounds.', 'simple-bmi-form' ),
      'pounds_min' => __( 'Pounds must at least 80.', 'simple-bmi-form' ),
      'enter_feet' => __( 'Enter a value for feet.', 'simple-bmi-form' ),
      'feet_min' => __( 'Feet must be greater than 3.', 'simple-bmi-form' ),
      'feet_max' => __( 'Feet must be less than 8.', 'simple-bmi-form' ),
      'inches_not_neg' => __( 'Inches must not be negative.', 'simple-bmi-form' ),
      'inches_max' => __( 'Inches must be less than 12.', 'simple-bmi-form' ),
      'enter_kilograms' => __( 'Enter a value for kilograms.', 'simple-bmi-form' ),
      'kilograms_min' => __( 'Kilograms must not be less than 35.', 'simple-bmi-form' ),
      'enter_metres' => __( 'Enter a value for metres.', 'simple-bmi-form' ),
      'metres_min' => __( 'Metres must be greater than 0.9.', 'simple-bmi-form' ),
      'metres_max' => __( 'Metres must be less than 2.4.', 'simple-bmi-form' ),
      'result' => __( 'Your BMI is', 'simple-bmi-form' )
    );
    wp_localize_script( 'sbf_public_js', 'translations', $translation_array );
    wp_register_style( 'sbf_public_css', SBF_URL.'css/public.css', array(), $sbf_version );
  }

  // enqueue scripts
  add_action( 'admin_enqueue_scripts', 'sbf_admin_enque_scripts' );
  function sbf_admin_enque_scripts() {
    wp_enqueue_script( 'sbf_admin_js' );
  }

  add_action( 'wp_enqueue_scripts', 'sbf_enqueue_scripts' );
  function sbf_enqueue_scripts() {
    wp_enqueue_script( 'sbf_public_js' );
    wp_enqueue_style( 'sbf_public_css' );
    // custom css
    $font_size = get_option( 'sbf_font_size' );
    $background_colour = get_option( 'sbf_background_colour' );
    $border_width = get_option( 'sbf_border_width' );
    $border_colour = get_option( 'sbf_border_colour' );
    $custom_css = '.sbf_form {'.PHP_EOL;
    $custom_css .= '  padding: 0;'.PHP_EOL;
    $custom_css .= '  margin: 0;'.PHP_EOL;
    $custom_css .= '  font-size: '.$font_size.'px;'.PHP_EOL;
    $custom_css .= '  background-color: '.$background_colour.';'.PHP_EOL;
    $custom_css .= '  border: '.$border_width.'px solid '.$border_colour.';'.PHP_EOL;
    $custom_css .= '}';
    wp_add_inline_style( 'sbf_public_css', $custom_css );
  } // end function

  include_once( 'includes/form.php' );
  if( is_admin() ) {
    include_once( 'includes/settings.php' );
  }

  // add pages to admin menu
  add_action( 'admin_menu', 'sbf_register_menu_items' );
  function sbf_register_menu_items() {
    // add_options_page( page_title, menu_title, capability, menu_slug, function);
    add_options_page ( 'Settings', 'Simple BMI Form', 'manage_options', 'simple-bmi-form', 'sbf_settings' );
  } // end function
  