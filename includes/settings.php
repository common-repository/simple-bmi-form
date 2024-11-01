<?php
  // Simple BMI Form
  // Topcode Website Services

  defined( 'ABSPATH' ) or die( 'Direct access is not permitted' );

  // set default options
  // add_option() will not change the option if it exists

  add_option( 'sbf_default_units', 0 );
  add_option( 'sbf_font_size', 15 );
  add_option( 'sbf_border_width', 1 ); // Blue
  add_option( 'sbf_background_colour', '#f4ffff' ); // very light blue
  add_option( 'sbf_border_colour', '#a9a9a9' ); // DarkGray
  add_option( 'sbf_show_wiki_link', 1 );

  function sbf_settings() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have permission to access this page.', 'simple-bmi-form' ) );
    }
    print '<div class="wrap">';
    print '<h2>'.__( 'Simple BMI Calculator', 'simple-bmi-form' ).'</h2>'.PHP_EOL;
    print '<form method="post" action="options.php" enctype="multipart/form-data" novalidate="novalidate">'.PHP_EOL;
    // settings_fields( option_group )
    settings_fields( 'sbf_general' );
    // do_settings_sections( page )
    do_settings_sections( 'sbf_general' );
    submit_button();
    print '</form>'.PHP_EOL;
    echo '</div>'.PHP_EOL; // end wrap div
  } // end function

  // setup the settings

  function sbf_setup_setting( $data ) {
    // add_settings_field( id, title, callable, page, section, array $args )
    $args = array( 'id' => $data['name'], 'name' => $data['name'], 'current_value' => get_option( $data['name'] ), 'help' =>  $data['help'] );
    add_settings_field( $data['name'], $data['label'], $data['input'], $data['page'], $data['section'], $args );
    // register_setting( option_group, option_name, array $args )
    $args = array('type' => 'string', 'description' => __( 'BMI Form', 'simple-bmi-form' ), 'sanitize_callback' => $data['validate'], 'show_in_rest' => false, 'default' => get_option( $data['name'] ) );
    register_setting( $data['group'], $data['name'], $args );
  }

  add_action( 'admin_init', 'sbf_setup_settings' );
  function sbf_setup_settings() {

    // general tab

    // add_settings_section( id, title, callback, page )
    $section_title = __( 'General', 'simple-bmi-form' );
    add_settings_section( 'sbf_general', $section_title, '', 'sbf_general' );

    // default units
    $data = array (
      'group' => 'sbf_general',
      'page' => 'sbf_general',
      'section' => 'sbf_general',
      'name' => 'sbf_default_units',
      'label' => __( 'Default units', 'simple-bmi-form' ),
      'input' => 'sbf_default_units_input',
      'help' => '',
      'validate' => 'sbf_validate_default_units'
    );
    sbf_setup_setting ( $data );

    // font size
    $data = array (
      'group' => 'sbf_general',
      'page' => 'sbf_general',
      'section' => 'sbf_general',
      'name' => 'sbf_font_size',
      'label' => __( 'Font size', 'simple-bmi-form' ),
      'input' => 'sbf_positive_integer_input',
      'help' => __( 'The font size in pixels. Must be between 6 and 60. The default is 14.', 'simple-bmi-form' ),
      'validate' => 'sbf_validate_font_size'
    );
    sbf_setup_setting ( $data );

    // background colour
    $data = array (
      'group' => 'sbf_general',
      'page' => 'sbf_general',
      'section' => 'sbf_general',
      'name' => 'sbf_background_colour',
      'label' => __( 'Background colour', 'simple-bmi-form' ),
      'input' => 'sbf_colour_input',
      'help' => __( 'Background colour', 'simple-bmi-form' ),
      'validate' => 'sbf_validate_colour'
    );
    sbf_setup_setting ( $data );

    // border width
    $data = array (
      'group' => 'sbf_general',
      'page' => 'sbf_general',
      'section' => 'sbf_general',
      'name' => 'sbf_border_width',
      'label' => __( 'Border width', 'simple-bmi-form' ),
      'input' => 'sbf_positive_integer_input',
      'help' => __( 'The border width in pixels. Must be between 0 and 20. The default is 2.', 'simple-bmi-form' ),
      'validate' => 'sbf_validate_border_width'
    );
    sbf_setup_setting ( $data );

    // border colour
    $data = array (
      'group' => 'sbf_general',
      'page' => 'sbf_general',
      'section' => 'sbf_general',
      'name' => 'sbf_border_colour',
      'label' => __( 'Border colour', 'simple-bmi-form' ),
      'input' => 'sbf_colour_input',
      'help' => __( 'Border colour', 'simple-bmi-form' ),
      'validate' => 'sbf_validate_colour'
    );
    sbf_setup_setting ( $data );

    // show wiki link
    $data = array (
      'group' => 'sbf_general',
      'page' => 'sbf_general',
      'section' => 'sbf_general',
      'name' => 'sbf_show_wiki_link',
      'label' => __( 'Wikipedia link', 'simple-bmi-form' ),
      'input' => 'sbf_radios_input',
      'help' => '',
      'validate' => 'sbf_validate_show_wiki_link'
    );
    sbf_setup_setting ( $data );

  } // end function

  // input functions

  function sbf_default_units_input( $args ) {
    $id = $args['id'];
    $name = $args['name'];
    $checked = $args['current_value'];
    sbf_print_radio_button( 0, $checked, $name, __( 'Imperial', 'simple-bmi-form' ) );
    sbf_print_radio_button( 1, $checked, $name, __( 'Metric', 'simple-bmi-form' ) );
    sbf_print_radio_button( 2, $checked, $name, __( 'US', 'simple-bmi-form' ) );
    sbf_print_help( $args );
  } // end function

  function sbf_text_input( $args ) {
    $id = $args['id'];
    $name = $args['name'];
    $current_value = $args['current_value'];
    print '<input type="text" id="'.$args['id'].'" name="'.$name.'" value="'.$current_value.'">'.PHP_EOL;
    sbf_print_help( $args );
  } // end function

  function sbf_positive_integer_input( $args ) {
    $id = $args['id'];
    $name = $args['name'];
    $current_value = $args['current_value'];
    print '<input type="text" id="'.$args['id'].'" name="'.$name.'" pattern="[0-9]{1-3}" value="'.$current_value.'">'.PHP_EOL;
    sbf_print_help( $args );
  } // end function

  function sbf_colour_input( $args ) {
    $id = $args['id'];
    $name = $args['name'];
    $current_value = $args['current_value'];
    print '<input type="text" id="'.$args['id'].'" name="'.$name.'" patter="#[0-9]{6}"value="'.$current_value.'" class="colour_field">'.PHP_EOL;
    sbf_print_help( $args );
  } // end function

  function sbf_radios_input( $args ) {
    $id = $args['id'];
    $name = $args['name'];
    $checked = $args['current_value'];
    sbf_print_radio_button( 0, $checked, $name, __( 'Don\'t show the Wikipedia link', 'simple-bmi-form' ) );
    sbf_print_radio_button( 1, $checked, $name, __( 'Show the Wikipedia link', 'simple-bmi-form' ) );
    sbf_print_help( $args );
  } // end function

  // print a radio button
  // if the value is the current_value, the button is selected
  function sbf_print_radio_button( $value, $current_value, $name, $text ) {
    print '<label>'.PHP_EOL;
    if ($value == $current_value) {
      print '<input type="radio" name="'.$name.'" value="'.$value.'" checked="checked" />'.PHP_EOL;
    } else {
      print '<input type="radio" name="'.$name.'" value="'.$value.'" />'.PHP_EOL;
    }
    print $text.PHP_EOL;
    print '</label>'.PHP_EOL;
    print '<br>'.PHP_EOL;
  } // end function

  // validation
  // add_settings_error( string $setting, string $code, string $message, string $type = 'error' );

  function sbf_validate_default_units( $value ) {
    return sbf_validate_integer( 'sbf_default_units', 'Default units', $value, 0, 2, 0 );
  } // end function

  function sbf_validate_font_size( $value ) {
    return sbf_validate_integer( 'sbf_font_size', 'Font size', $value, 6, 24, 14 );
  } // end function

  function sbf_validate_border_width( $value ) {
    return sbf_validate_integer( 'sbf_border_width', 'Border width', $value, 0, 10, 2 );
  } // end function

  function sbf_validate_show_wiki_link( $value ) {
    return sbf_validate_integer( 'sbf_show_wiki_link', 'Show Wiki Link', $value, 0, 1, 1 );
  } // end function

  function sbf_validate_integer( $slug, $name, $value, $min, $max, $default ) {
    $value = (int) sanitize_text_field( $value );
    if( $value < $min ) {
      add_settings_error( $slug, $slug, $name.' must not be less than '.$min, 'error' );
      $value = $default;
    }
    if( $value > $max ) {
      add_settings_error( $slug, $slug, $name.' must not be more than '.$max, 'error' );
      $value = $default;
    }
    return $value;
  } // end function

  function sbf_validate_colour( $value ) {
    // can only be a valid colour from a colour picker
    $value = trim( sanitize_text_field( $value ) );
    $length = strlen( $value );
    if( 7 != $length && 4 != $length ) {
      add_settings_error( 'colour', 'colour', 'Colour value is invalid', 'error' );
      $value = '#ffffff'; // White is the default colour
    }
    return $value;
  }

  // show help text

  function sbf_print_help ( $args ) {
    $help = $args['help'];
    if ( $help ) {
      print '<p class="sbf_help">'.$help.'</p>'.PHP_EOL;
    }
  }
