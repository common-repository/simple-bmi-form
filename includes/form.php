<?php
  // Simple BMI Form
  // Topcode Website Services

  defined( 'ABSPATH' ) or die( 'Direct access is not permitted' );

  add_action( 'widgets_init','sbf_register_bmi_widget' );
  function sbf_register_bmi_widget() {
    register_widget( 'Simple_BMI_Form' );
  } // end function

  class Simple_BMI_Form extends WP_Widget {

    // set up the widget's name etc
    public function __construct() {
      $widget_ops = array(
        'classname' => 'simple_bmi_form',
        'description' => __( 'A BMI calculator', 'simple-bmi-form' )
      );
      parent::__construct( 'simple_bmi_form', 'Simple BMI Form', $widget_ops );
    }

    // output the widget content
    public function widget( $args, $instance ) {
      print $args['before_widget'].PHP_EOL;
      if( $instance['title'] ) {
        print $args['before_title'].$instance['title'].$args['after_title'].PHP_EOL;
      }
      print sbf_simple_bmi_form();
      print $args['after_widget'].PHP_EOL;
    }

    // output the options form on admin
    public function form( $instance ) {
      $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'BMI Calculator', 'simple-bmi-form' );
      print '<p>'.PHP_EOL;
      print '<label for="'.$this->get_field_id( 'title' ).'">'.PHP_EOL;
      print __( 'Title:', 'simple-bmi-form' ).PHP_EOL;
      print '</label>'.PHP_EOL;
      print '<input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.$title.'">'.PHP_EOL;
      print '</p>'.PHP_EOL;
    } // end function

    // processing widget options on save
    public function update( $new_instance, $old_instance ) {
      return $new_instance;
    } // end function
  } // end class

  add_shortcode( 'simple_bmi_form', 'sbf_simple_bmi_form' );

  function sbf_simple_bmi_form() {
    $id = sbf_get_next_id();;

    ob_start();
    print '<div class="sbf_form">'.PHP_EOL;
    print '<div class="sbf_form_inner">'.PHP_EOL;

    print '<div>'.__( 'Valid for age 20 or older.', 'simple-bmi-form' ).'</div>'.PHP_EOL;

    // units switching buttons
    print '<div>'.__( 'Set units:', 'simple-bmi-form' ).'<br>'.PHP_EOL;
    print '<button id="sbf_button_0" class="sbf_button" onclick="sbf_set_units(0);">'.__( 'Imperial', 'simple-bmi-form' ).'</button>'.PHP_EOL;
    print '<button id="sbf_button_1" class="sbf_button" onclick="sbf_set_units(1);">'.__( 'Metric', 'simple-bmi-form' ).'</button>'.PHP_EOL;
    print '<button id="sbf_button_2" class="sbf_button" onclick="sbf_set_units(2);">'.__( 'US', 'simple-bmi-form' ).'</button>'.PHP_EOL;
    print '</div>'.PHP_EOL;
      
    // weight
    print '<div>'.__( 'Your weight:', 'simple-bmi-form' ).'<br>'.PHP_EOL;
    print '<span id="sbf_weight_input"></span>'.PHP_EOL;
    print '</div>'.PHP_EOL;

    // height
    print '<div>'.__( 'Your height:', 'simple-bmi-form' ).'<br>'.PHP_EOL;
    print '<span id="sbf_height_input"></span>'.PHP_EOL;
    print '</div>'.PHP_EOL;
  
    // calculate
    print '<div>'.PHP_EOL;
    print '<button class="button" onclick="sbf_calculate(0);">'.__( 'Calculate', 'simple-bmi-form' ).'</button>'.PHP_EOL;
    print '</div>'.PHP_EOL;
        
    // show wiki link
    if ( get_option( 'sbf_show_wiki_link' ) ) {
      print '<div>'.PHP_EOL;
      print __( 'Find out more on', 'simple-bmi-form' ).' <a href="https://en.wikipedia.org/wiki/Body_mass_index" target="_blank">Wikipedia</a>';
      print '</div>'.PHP_EOL;
    }

    // messages
    print '<div id="sbf_messages"></div>'.PHP_EOL;

    print '</div>'.PHP_EOL; // end sbf_form_inner
    print '</div>'.PHP_EOL; // end sbf_form
    
    // setup
    $default_units = get_option( 'sbf_default_units' );
    print '<script>'.PHP_EOL;
    print '<!--'.PHP_EOL;
    print 'jQuery(document).ready(function($) {'.PHP_EOL;
    print '  sbf_set_units('.$default_units.');'.PHP_EOL;
    print '} );'.PHP_EOL;
    print '//-->'.PHP_EOL;
    print '</script>'.PHP_EOL;

    return ob_get_clean();
  } // end function

  function sbf_get_next_id() {
    global $sbf_next_id;
    if( !isset( $sbf_next_id ) ) {
      $sbf_next_id = 0;
    } else {
      $sbf_next_id++;
    }
    return $sbf_next_id;
  } // end function