<?php
/*
Plugin Name: Change Cart Word for Woocommerce
Description: Change the word "Cart" in Woocommerce to the word or phrase of your choosing. Upon activation, the option is found in the General Settings of Woocommerce.
Author: Do Good Design Co.
Version: 1.0.0
Author URI: https://dogood.design
License: GPLv2
Text Domain: dgd-wc-add-cart-word-setting
*/

// Add an option to the Woocommerce General Settings
function dgd_wc_add_cart_word_setting( $settings ) {
  $updated_settings = array();

  foreach ( $settings as $section ) {
    // at the bottom of the General Options section
    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

      $updated_settings[] = array(
        'title'    => __( 'Replace the word "Cart"', 'wc_cart_word', 'dgd-wc-add-cart-word-setting' ),
        'desc_tip' => __( 'If you would like to replace the word "Cart" throughout Woocommerce, enter a different word here.', 'dgd-wc-add-cart-word-setting' ),
        'id'       => 'wc_cart_word',
        'type'     => 'text',
        'css'      => 'min-width:300px;',
        'desc'     => 'This option was added by the Change Cart Word for Woocommerce plugin, created by <a href="https://dogood.design" target="_blank">Do Good Design Co.</a>.',
      );
    }

    $updated_settings[] = $section;
  }

  return $updated_settings;
}
add_filter( 'woocommerce_general_settings', 'dgd_wc_add_cart_word_setting' );

// Use the gettext filter to change every occurrence of the word "Cart".
function change_translate_cart_to_basket( $translated, $text, $domain ) {
  $cart_word = get_option( 'wc_cart_word' );
  if ( $domain == 'woocommerce' && stripos( $translated, 'cart' ) !== false ) {
    $translated = str_replace( 'cart', strtolower( $cart_word ), $translated );
    $translated = str_replace( 'Cart', ucwords( $cart_word ), $translated );
  }
  return $translated;
}

// Change the text in notices because some is not caught by the gettext filter.
function change_notice_cart_to_basket( $message ) {
  $cart_word = get_option( 'wc_cart_word' );
  if ( stripos( $message, 'cart' ) !== false ) {
    $message = str_replace( 'cart', strtolower( $cart_word ), $message );
    $message = str_replace( 'Cart', ucwords( $cart_word ), $message );
  }
  return $message;
}

if ( get_option( 'wc_cart_word' ) ) {
  add_filter( 'gettext', 'change_translate_cart_to_basket', 10, 3 );
  add_filter( 'woocommerce_add_error', 'change_notice_cart_to_basket', 10, 1 );
  add_filter( 'woocommerce_add_notice', 'change_notice_cart_to_basket', 10, 1 );
  add_filter( 'woocommerce_add_success', 'change_notice_cart_to_basket', 10, 1 );
}
