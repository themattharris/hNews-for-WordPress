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
    add_action('admin_init', array($this, 'admin_init'));
    add_action('admin_menu', array($this, 'admin_menu'));
    add_action('save_post', array($this, 'save_post'), 10, 2);

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
   * Register the hNews Settings
   */
  function admin_init() {
    register_setting('hnews_options', 'hnews_options', array($this, 'options_validate'));
    add_settings_section('hnews_settings_main', __('Main Settings'), array($this, 'render_main_section_text'), 'hnews_page');
    add_settings_field('principles_url', __('Principles URL'), array($this, 'render_principles_url'), 'hnews_page', 'hnews_settings_main');
    add_settings_field('license_url', __('License URL'), array($this, 'render_license_url'), 'hnews_page', 'hnews_settings_main');
    add_settings_field('license_text', __('License Label'), array($this, 'render_license_text'), 'hnews_page', 'hnews_settings_main');

    add_settings_section('hnews_settings_org', __('Source Organisation'), array($this, 'render_org_section_text'), 'hnews_page');
    add_settings_field('org_name', __('Organization Name:'), array($this, 'render_org_name'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('org_unit', __('Organization Unit:'), array($this, 'render_org_unit'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('email', __('Email:'), array($this, 'render_email'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('url', __('URL:'), array($this, 'render_url'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('post_office_box', __('PO Box:'), array($this, 'render_post_office_box'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('extended_address', __('Apartment/Suite Number:'), array($this, 'render_extended_address'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('street_address', __('Street Address:'), array($this, 'render_street_address'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('locality', __('Locality/Town:'), array($this, 'render_locality'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('region', __('Region/County:'), array($this, 'render_region'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('postal_code', __('Postal/Zip Code:'), array($this, 'render_postal_code'), 'hnews_page', 'hnews_settings_org');
    add_settings_field('country_name', __('Country:'), array($this, 'render_country_name'), 'hnews_page', 'hnews_settings_org');
  }

  /**
   * Validation and sanitisation for the hNews options
   */
  function options_validate($fields) {
    foreach ($fields as $k => &$v) {
      $v = stripslashes(wp_filter_post_kses(addslashes(trim($v)))); // wp_filter_post_kses() expects slashed
    }
    return $fields;
  }

  /**
   * The text to display in the header of the options section
   */
  function render_main_section_text() {
    echo '<p>'.__('The URLs and labels you enter here will be used as the default value when adding a new post.').'</p>';
  }
  // Option fields rendering code
  function render_principles_url() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_principles_url' name='hnews_options[principles_url]' size='70' type='text' value='{$options['principles_url']}' class='code' />";
  }
  function render_license_url() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_license_url' name='hnews_options[license_url]' size='70' type='text' value='{$options['license_url']}' class='code' />";
  }
  function render_license_text() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_license_text' name='hnews_options[license_text]' size='40' type='text' value='{$options['license_text']}' />";
  }

  function render_org_section_text() {
    echo '<p>'.__('The source organisation you enter here will be used as the default organisation when adding a new post.').'</p>';
  }
  // Option fields rendering code
  function render_org_name() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_org_name' name='hnews_options[org_name]' size='40' type='text' value='{$options['org_name']}' />";
  }
  function render_org_unit() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_org_unit' name='hnews_options[org_unit]' size='40' type='text' value='{$options['org_unit']}' />";
  }
  function render_email() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_email' name='hnews_options[email]' size='70' type='text' value='{$options['email']}' class='code' />";
  }
  function render_url() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_url' name='hnews_options[url]' size='70' type='text' value='{$options['url']}' class='code' />";
  }
  function render_post_office_box() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_post_office_box' name='hnews_options[post_office_box]' size='40' type='text' value='{$options['post_office_box']}' />";
  }
  function render_extended_address() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_extended_address' name='hnews_options[extended_address]' size='40' type='text' value='{$options['extended_address']}' />";
  }
  function render_street_address() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_street_address' name='hnews_options[street_address]' size='40' type='text' value='{$options['street_address']}' />";
  }
  function render_locality() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_locality' name='hnews_options[locality]' size='40' type='text' value='{$options['locality']}' />";
  }
  function render_region() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_region' name='hnews_options[region]' size='40' type='text' value='{$options['region']}' />";
  }
  function render_postal_code() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_postal_code' name='hnews_options[postal_code]' size='40' type='text' value='{$options['postal_code']}' />";
  }
  function render_country_name() {
    $options = get_option('hnews_options');
    echo "<input id='hnews_country_name' name='hnews_options[country_name]' size='40' type='text' value='{$options['country_name']}' />";
  }

  /**
   * Register the meta boxes and admin options
   */
  function admin_menu() {
    add_meta_box('hnews_main', __('hNews'), array($this, 'meta_box_main'), 'post', 'normal', 'high');
    add_meta_box('hnews_geo', __('Geolocation'), array($this, 'meta_box_geo'), 'post', 'side', 'high');

    add_options_page('hNews Defaults', 'hNews', 'manage_options', 'hNews', array($this, 'hnews_options_page'));
  }

  /**
   * Process the post meta on save
   *
   * @param string $post_ID the ID of the post to be updated or that was just saved
   * @param string $post the post object
   */
  function save_post($post_ID, $post) {
    // url defaults are read from wp_options
    $defaults = array(
      'geo_latitude'         => 0,
      'geo_longitude'        => 0,
      'hnews_principles_url' => '',
      'hnews_license_url'    => '',
      'hnews_license_text'   => '',
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
   * The main hNews meta box rendering function
   */
  function meta_box_main($post) {
    // fallback for when the filter for post_to_edit isn't present
    if ( ! isset($post->hnews_principles_url)) {
      $this->post_to_edit($post);
    }

    $options = get_option('hnews_options');
    $principles_url = empty($post->hnews_principles_url) ? $options['principles_url'] : $post->hnews_principles_url;
    $license_url = empty($post->hnews_license_url) ? $options['license_url'] : $post->hnews_license_url;
    $license_text = empty($post->hnews_license_text) ? $options['license_text'] : $post->hnews_license_text;
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

  /**
   * The hNews admin options rendering function
   */
  function hnews_options_page() {
    require 'admin_options.php';
  }
}

include 'template_functions.php';

new hNews();

?>