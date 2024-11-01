// Simple BMI Form
// Topcode Website Services

var sbf_messages = [];
var sbf_units_code = 0;

function sbf_set_units( code ) {
  sbf_units_code = code;
  var i, element, html_wt, html_ht;
  for( i = 0; i < 3; i++ ) {
    element = document.getElementById( "sbf_button_" + i );
    if( code == i ) {
      element.classList.add( "selected" );
    } else {
      element.classList.remove( "selected" );
    }
  }
  switch( code ) {
    case 0:
      // imperial
      html_wt = '<input type="text" id="sbf_stones" value="" /> ' + translations.stones;
      html_wt += '&nbsp; <input type="text" id="sbf_pounds" value=""/> ' + translations.pounds;
      html_ht = '<input type="text" id="sbf_feet" value="" /> ' + translations.feet;
      html_ht += '&nbsp; <input type="text" id="sbf_inches" value="" /> ' + translations.inches;
      break;
    case 1:
      // metric
      html_wt = '<input type="text" id="sbf_kilograms" value="" /> ' + translations.kilograms;
      html_ht = '<input type="text" id="sbf_metres" value="" /> ' + translations.metres ;
      break;
    case 2:
      // US
      html_wt = '<input type="text" id="sbf_pounds" value=""/> ' + translations.pounds ;
      html_ht = '<input type="text" id="sbf_feet" value="" /> ' + translations.feet;
      html_ht += '&nbsp; <input type="text" id="sbf_inches" value="" /> ' + translations.inches;
      break;
    default:
      html_wt = "";
      html_ht = "";
      console.log( "Simple BMI: Error: Unknown unit code" );
  }
  document.getElementById( "sbf_weight_input" ).innerHTML = html_wt;
  document.getElementById( "sbf_height_input" ).innerHTML = html_ht;
} // end function

function sbf_calculate() {
  var stones, pounds, kilograms, feet, inches, metres, bmi;
  sbf_messages = [];
  switch( sbf_units_code ) {
    case 0:
      // imperial
      stones = Number( document.getElementById( "sbf_stones" ).value );
      sbf_check_stones( stones );
      pounds = Number( document.getElementById( "sbf_pounds" ).value );
      sbf_check_pounds( pounds );
      kilograms = ( stones * 14 + pounds ) / 2.20462;
      feet = Number( document.getElementById( "sbf_feet" ).value );
      sbf_check_feet( feet );
      inches = Number( document.getElementById( "sbf_inches" ).value );
      sbf_check_inches( inches );
      metres = ( feet + inches / 12 ) * 0.3048;
      break;
    case 1:
      // metric
      kilograms = Number( document.getElementById( "sbf_kilograms" ).value );
      sbf_check_kilograms( kilograms );
      metres = Number( document.getElementById( "sbf_metres" ).value );
      sbf_check_metres( metres );
      break;
    case 2:
      // US
      pounds = Number( document.getElementById( "sbf_pounds" ).value );
      sbf_check_us_pounds( pounds );
      kilograms = pounds / 2.20462;
      feet = Number( document.getElementById( "sbf_feet" ).value );
      sbf_check_feet( feet );
      inches = Number( document.getElementById( "sbf_inches" ).value );
      sbf_check_inches( inches );
      metres = ( feet + inches / 12 ) * 0.3048;
      break;
    default:
      console.log( "Simple BMI: Error: Unknown unit code" );
  }
  if( sbf_messages.length ) {
    document.getElementById( "sbf_messages" ).innerHTML = '<span class="sbf_error">' + sbf_messages.join( "<br>" ) + "</span>";
  } else {
    bmi = kilograms / ( metres * metres );
    bmi = bmi.toFixed(1);
    sbf_messages.push( translations.result + " " + bmi );
    // diagnoses
    if ( bmi < 15.0 ) {
      sbf_messages.push( translations.very_severly_underweight );
    } else if ( bmi < 16.0 ) {
      sbf_messages.push( translations.severly_underweight );
    } else if ( bmi < 18.5 ) {
      sbf_messages.push( translations.underweight );
    } else if ( bmi < 25 ) {
      sbf_messages.push( translations.normal );
    } else if ( bmi < 30 ) {
      sbf_messages.push( translations.overweight );
    } else if ( bmi < 35 ) {
      sbf_messages.push( translations.obese_I );
    } else if ( bmi < 40 ) {
      sbf_messages.push( translations.obese_II );
    } else {
      sbf_messages.push( translations.obese_III );
    }
    document.getElementById( "sbf_messages" ).innerHTML = '<span class="sbf_help">' + sbf_messages.join( "<br>" ) + "</span>";
  }
} // end function

function sbf_check_stones( stones ) {
  if( stones === 0 ) {
    sbf_messages.push( translations.enter_stones );
    return;
  }
} // end function

function sbf_check_pounds( pounds ) {
  if( pounds > 14 ) {
    sbf_messages.push( translations.pounds_max );
    return;
  }
  if( pounds < 0 ) {
    sbf_messages.push( translations.pounds_not_neg );
    return;
  }
} // end function

function sbf_check_us_pounds( pounds ) {
  if( pounds === 0 ) {
    sbf_messages.push( translations.enter_pounds );
    return;
  }
  if( pounds < 80 ) {
    sbf_messages.push( translations.pounds_min );
    return;
  }
} // end function

function sbf_check_feet( feet ) {
  if( feet === 0 ) {
    sbf_messages.push( translations.enter_feet );
    return;
  }
  if( feet < 3) {
    sbf_messages.push( translations.feet_min );
    return;
  }
  if( feet > 7 ) {
    sbf_messages.push( translations.feet_max );
    return;
  }
} // end function

function sbf_check_inches( inches ) {
  if( inches < 0 ) {
    sbf_messages.push( translations.inches_not_neg );
    return;
  }
  if( inches > 12 ) {
    sbf_messages.push( translations.inches_max );
    return;
  }
} // end function

function sbf_check_kilograms( kilograms ) {
  if( kilograms === 0 ) {
    sbf_messages.push( translations.enter_kilograms );
  }
  if( kilograms < 35 ) {
    sbf_messages.push( translations.kilograms_min );
    return;
  }
} // end function

function sbf_check_metres( metres ) {
  if( metres === 0 ) {
    sbf_messages.push( translations.enter_metres );
    return;
  }
  if( metres < 0.9 ) {
    sbf_messages.push( translations.metres_min );
    return;
  }
  if( metres > 2.4 ) {
    sbf_messages.push( translations.metres_max );
    return;
  }
} // end function
