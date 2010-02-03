<?php

/*
Plugin Name: hNews
Plugin URI: http://valueaddednews.org
Description:
Author: Matt Harris
Version: 1.0
Author URI: http://themattharris.com
*/

class hNews {

  /**
   * Register our actions with the WordPress hooks
   */
  function __construct() {
    add_action('save_post', array($this, 'save_post'), 10, 2);
    add_action('admin_menu', array($this, 'add_custom_boxes'));

    // add the custom css to style our boxes
    add_action('admin_head-post.php', array($this, 'add_css'));
    add_action('admin_head-post-new.php', array($this, 'add_css'));

    // add the custom js required for our boxes
    add_action('admin_print_scripts-post.php', array($this, 'add_js'));
    add_action('admin_print_scripts-post-new.php', array($this, 'add_js'));
    
    // This filter needs adding to WordPress core. line 395 wp-admin/includes/post.php
    // add_filter('post_to_edit', array($this, 'post_to_edit'));
    
    add_filter('posts_results', array($this, 'posts_results'));
  }

  /**
   * Add the custom CSS we need to render our meta boxes
   */
  function add_css() {
    require 'hnews_css.php';
  }

  /**
   * Add the custom Javascript we need to render our meta boxes and register
   * our hNews javascript file.
   */
  function add_js() {
    require 'hnews_js.php';
    wp_enqueue_script('hNews', '/'.PLUGINDIR.'/hNews/hnews.js');
  }

  /**
   * Register the meta boxes
   */
  function add_custom_boxes() {
    add_meta_box('hnews_main', __('hNews'), array($this, 'meta_box_main'), 'post', 'normal', 'high');
    add_meta_box('hnews_geo', __('Geolocation'), array($this, 'meta_box_geo'), 'post', 'side', 'high');
  }

  /**
   * Process the post meta on save
   *
   * @return void
   */
  function save_post($post_ID, $post) {
    // url defaults are read from wp_options
    $defaults = array(
      'geo_latitude'         => 0,
      'geo_longitude'        => 0,
      'hnews_principles_url' => get_option('hnews_principles_url'),
  		'hnews_license_url'    => get_option('hnews_license_url'),
  		'hnews_license_text'   => get_option('hnews_license_text'),
    );

    // parse the args through WordPress parsing function and sanitize
  	$postarr = wp_parse_args($_POST, $defaults);
  	$postarr = sanitize_post($postarr, 'db');
  	// drop fields we don't want
  	$postarr = array_intersect_key($postarr, $defaults);

  	// only save the fields which are different. This also catches any instances
  	// where the hnews fields are not added to a page or custom post type (WP 3)
  	$diffs = array_diff($postarr, $defaults);

  	// save to the database, renaming all keys from 'key' to '_key'
  	foreach ($diffs as $k => $v) {
  	  add_post_meta($post_ID, "_$k", $v, true) or update_post_meta($post_ID, "_$k", $v);
  	}
  }

  /**
   * The main hNews meta box rendering function
   */
  function meta_box_main($post) {
    // fallback for when the filter for post_to_edit isn't present
    if ( ! isset($post->hnews_principles_url)) {
      $this->post_to_edit($post);
    }
    require 'box_main.php';
  }

  /**
   * The hNews Geolocation meta box rendering function
   */
  function meta_box_geo($post) {
    // fallback for when the filter for post_to_edit isn't present
    if ( ! isset($post->geo_latitude)) {
      $this->post_to_edit($post);
    }
    require 'box_geo.php';
  }
  
  /**
   * Add our extra fields to the post being edited
   */
  function post_to_edit($post) {
    $id = $post->ID;
    $post->geo_latitude = get_post_meta( $id, '_geo_latitude', true );
    $post->geo_longitude = get_post_meta( $id, '_geo_longitude', true );
    $post->hnews_principles_url = get_post_meta( $id, '_hnews_principles_url', true );
    $post->hnews_license_url = get_post_meta( $id, '_hnews_license_url', true );
    $post->hnews_license_text = get_post_meta( $id, '_hnews_license_text', true );
    return $post;
  }
  
  /**
   * Process all post results and add the custom fields
   */
  function posts_results($posts) {
    foreach ($posts as &$post) {
      $post = $this->post_to_edit($post);
    }
    return $posts;
  }

}

include 'template_functions.php';

new hNews();

?>