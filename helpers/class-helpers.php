<?php 
/**
 * Functions that helps to ease theme development.
 *
 * @package _xe
 */

namespace Helpers;

if (!class_exists('Xe_Helpers')) :

class Xe_Helpers {

  /**
   * Auto load files from a directory.
   */
  public static function auto_load_files($path) {

    $files = glob($path);

    foreach ($files as $file) {
      if (basename($file) == 'index.php') continue;
      require($file); 
    }

  }

  /**
   * Array of header or footer styles.
   */
  public static function files_array($prefix, $override = false) {

    $files = glob( get_stylesheet_directory() . '/views/'.$prefix.'*.php' );
    $files = glob( get_template_directory() . '/views/'.$prefix.'*.php' );

    if ($override == true) {
      $output = array('0' => 'Default');
    } else {
      $output = array();
    }

    foreach ($files as $file) {
      // if (basename($file) == 'index.php') continue;
      $file = basename($file, '.php');
      $id = str_replace(array('-', $prefix), '', $file);
      $file = ucwords(str_replace('-', ' ', $file));
      $file = ($file == 'Header') ? 'Header Primary' : $file;
      $file = ($file == 'Footer') ? 'Footer Primary' : $file;
      $file = ($file == 'Archive') ? 'Archive Primary' : $file;
      $file = str_replace(array('Header ', 'Footer ', 'Archive '), '', $file);
      $output[$id] = $file . ' Style';
    }

    return $output;

  }

  /**
   * Get and list menu locations.
   *
   * Will only work after init and wp hook.
   */
  public static function menu_locations($override = false) {

    global $_wp_registered_nav_menus;

    if ($override == true) :
      $data['0'] = 'Default';
    endif;

    foreach ( $_wp_registered_nav_menus as $k => $v ) {
      $data[$k] = $v;
    }

    return $data;

  }

  /**
   * Get list of revolution sliders.
   */
  public static function rev_sliders() {

    $data = array();
    $data['none'] = esc_html__( 'None', '_xe' );

    if ( class_exists('RevSlider') ) :

      $slider = new \RevSliderSlider();
      $sliders = $slider->getArrSlidersShort();

      if (!empty($sliders)) {
        foreach ($sliders as $key => $val) {
          $data[$key] = $val;
        }
      }

    endif;
    
    return $data;

  }

  /**
   * Get list of sidebars.
   *
   * Will only work after init and wp hook.
   */
  public static function get_sidebars() {

    global $wp_registered_sidebars;
    $data = array();

    foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
      $key = $sidebar['id'];
      $val = $sidebar['name'];
      $data[$key] = $val;
    }

    return $data;

  }

  /**
   * Minifying styles 
   */
  public static function minify_css($css) {

    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    $css = str_replace(array('{ ', ' {'), '{', $css);
    $css = str_replace(array('} ', ' }'), '}', $css);
    $css = str_replace('; ', ';', $css);
    $css = str_replace(': ', ':', $css);
    $css = str_replace(', ', ',', $css);
    $css = str_replace(array('> ', ' >'), '>', $css);
    $css = str_replace(array('+ ', ' +'), '+', $css);
    $css = str_replace(array('~ ', ' ~'), '~', $css);
    $css = str_replace(';}', '}', $css);

    return $css;

  }

  /**
   * Hex color to rgb conversion
   */
  public static function hex2rgb($color) {

    if ( $color[0] == '#' ) {
      $color = substr( $color, 1 );
    }
    if ( strlen( $color ) == 6 ) {
      list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
      list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
      return false;
    }

    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    
    return $r.', '.$g.', '.$b;

  }

  /**
   * Darken or Lighten Color
   */
  public static function darken($color, $dif=20) {

    $color = str_replace('#','', $color);
    $rgb = '';

    if (strlen($color) != 6) {

      // reduce the default amount a little
      $dif = ($dif==20)?$dif/10:$dif;

      for ($x = 0; $x < 3; $x++) {

        $c = hexdec(substr($color,(1*$x),1)) - $dif;
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= $c;

      }

    } else {

      for ($x = 0; $x < 3; $x++) {

        $c = hexdec(substr($color, (2*$x),2)) - $dif;
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;

      }

    }

    return '#'.$rgb;

  }

  /**
   * Adjusting spacing of classes
   */
  public static function classes( $classes = array() ) {

    $classes = implode(' ', $classes);
    $classes = trim( preg_replace('/\s+/', ' ', $classes) );

    return $classes;

  }

}

endif;
