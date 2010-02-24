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
  var $supported_fields_geo = array(
    'geo_latitude'  => 'Latitude',
    'geo_longitude' => 'Longitude',
  );

  var $supported_fields_main = array(
    'principles_url'  => 'Principles URL',
    'principles_text' => 'Principles Label',
    'license_url'     => 'License URL',
    'license_text'    => 'License Name',
  );

  var $supported_fields_org = array(
    'org_name'         => 'Organization Name',
    'url'              => 'URL',
    'org_unit'         => 'Organization Unit',
    'email'            => 'Email',
    'tel'              => 'Phone',
    'post_office_box'  => 'PO Box',
    'extended_address' => 'Apt/Suite Number',
    'street_address'   => 'Street Address',
    'locality'         => 'City/Town',
    'region'           => 'State/County',
    'postal_code'      => 'Zip/Postal Code',
    'country_name'     => 'Country',
  );

  /**
   * PHP4 constructor; Calls PHP 5 style constructor
   */
  function hNews() {
    $this->__construct();
  }

  /**
   * Register our actions with the WordPress hooks
   */
  function __construct() {
    add_action('admin_init', array($this, 'admin_init'));
    add_action('admin_menu', array($this, 'admin_menu'));
    add_action('save_post', array($this, 'save_post'), 10, 2);

    // add the custom css to style our boxes
    add_action('admin_print_styles-settings_page_hNews', array($this, 'add_css'));
    add_action('admin_print_styles-post.php', array($this, 'add_css'));
    add_action('admin_print_styles-post-new.php', array($this, 'add_css'));

    // add the custom js required for our boxes
    add_action('admin_print_scripts-settings_page_hNews', array($this, 'add_js'));
    add_action('admin_print_scripts-post.php', array($this, 'add_js'));
    add_action('admin_print_scripts-post-new.php', array($this, 'add_js'));

    // This filter needs adding to WordPress core. line 395 wp-admin/includes/post.php
    // add_filter('post_to_edit', array($this, 'post_to_edit'));

    // custom ajax handling for looking up the title of a webpage. The hnews is the querystring argument 'action'
    add_action('wp_ajax_hnews', array($this, 'wp_ajax'));

    add_filter('posts_results', array($this, 'posts_results'));
  }

  function render($what) {
    $options = get_option('hnews_options');

    $class = (strstr($what, 'url') || strstr($what, 'email')) ? 'class="code"' : '';
    echo "<input id='hnews_$what' name='hnews_options[$what]' size='70' type='text' value='{$options[$what]}'$class />";
  }

  /**
   * Register the hNews Settings
   */
  function admin_init() {
    register_setting('hnews_options', 'hnews_options', array($this, 'options_validate'));
    add_settings_section('hnews_main', __('Main Settings'), array($this, 'render_main_section_text'), 'hnews_page');
    foreach ($this->supported_fields_main as $k => $v) {
      add_settings_field($k, __($v), array($this, 'render'), 'hnews_page', 'hnews_main', $k);
    }

    add_settings_section('hnews_org', __('Source Organisation'), array($this, 'render_org_section_text'), 'hnews_page');
    foreach ($this->supported_fields_org as $k => $v) {
      add_settings_field($k, __($v), array($this, 'render'), 'hnews_page', 'hnews_org', $k);
    }

    add_settings_section('hnews_geo', __('Geolocation'), array($this, 'render_geo_section_text'), 'hnews_page');
    foreach ($this->supported_fields_geo as $k => $v) {
      add_settings_field($k, __($v), array($this, 'render'), 'hnews_page', 'hnews_geo', $k);
    }
  }

  /**
   * Ajax handler for the hnews action.
   * Doing it this way is better as this only runs if the get argument is set - and
   * allows WordPress to check the user is logged in
   */
  function wp_ajax() {
    if ($response = wp_remote_get(@$_GET['hnews_url'])) {
      if (preg_match('$<title.*?>(.+?)</title>$is', $response['body'], $matches) == 1) {
        echo preg_replace('$\s\s+$',' ', strip_tags(trim($matches[1])));
      }
    }
    // stop processing.
    die();
  }

  /**
   * Validation and sanitisation for the hNews options. PHP 4 compatible
   */
  function options_validate($fields) {
    foreach ($fields as $k => $v) {
      $fields[$k] = stripslashes(wp_filter_post_kses(addslashes(trim($v)))); // wp_filter_post_kses() expects slashed
    }
    return $fields;
  }

  /**
   * The text to display in the header of the options section
   */
  function render_main_section_text() {
    echo '<p>'.__('The URLs and labels you enter here will be used as the default value when adding a new post.').'</p>';
  }
  function render_org_section_text() {
    echo '<p>'.__('These are the details of the publisher who should be used for all new posts. All fields are optional and you can change them from within a post. ').'</p>';
  }
  function render_geo_section_text() {
    echo '<p>'.__('The location you enter here will be used as the co-ordinates in the dateline for the story being published.').'</p>';
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
    $defaults = array();
    foreach ($this->supported_fields_geo as $k => $v) {
      $defaults["hnews_$k"] = 0;
    }
    foreach ($this->supported_fields_main + $this->supported_fields_org as $k => $v) {
      $defaults["hnews_$k"] = strstr($k, 'url') ? 'http://' : "\n";
    }

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
      if (empty($v)) {
        delete_post_meta($post_ID, "_$k");
      } else {
        add_post_meta($post_ID, "_$k", $v, true) or update_post_meta($post_ID, "_$k", $v);
      }
    }
  }

  /**
   * Add the custom CSS we need to render our meta boxes
   */
  function add_css() { ?>
    <style type="text/css" media="screen">
      #hnews_geo fieldset div {
        width: 50%;
        float: left;
      }

      #hnews_geo #geo_addr_wrap {
        clear: both;
        width: auto;
        float: none;
      }

      #hnews_main fieldset {
        margin-bottom: 2em;
      }

      #hnews_main legend {
        font-weight: bold;
        padding-bottom: 0.5em;
      }

      #hnews_map {
        display: block;
        height: 200px;
        width: 100%;
        border: 1px solid rgb(128,128,128);
        margin-bottom: 1em;
      }

      #hnews_source_org {
        clear: both;
      }

      #hnews_org_basic, #hnews_org_more {
        overflow: auto;
      }
      #hnews_org_basic div {
        width: 50%;
        float: left;
      }

      #hnews_org_more {
        padding-top: 1em;
      }

      #hnews_org_more_hint {
        text-decoration: none;
      }

      #hnews_org_other, #hnews_org_address,
      #hnews_principles, #hnews_license {
        width: 50%;
        float: left;
      }

      #hnews_org_basic div, #hnews_org_more div,
      #hnews_principles div, #hnews_license div,
      #hnews_geo fieldset div {
        margin-bottom: 1em;
      }
      #hnews_geo #geo_addrhint {
        margin-bottom: 0;
      }

      #hnews_org_basic label, #hnews_org_more label,
      #hnews_principles label, #hnews_license label {
        display: block;
      }

      #hnews_geo fieldset div#geo_addrhint {
        float: none;
        width: auto;
      }

      #geo_addr {
        width: 180px;
      }

      #hnews_org_basic input, #hnews_org_more input,
      #hnews_principles input, #hnews_license input,
      body.settings_page_hNews tr input {
        width: 90%;
        max-width: 400px;
      }
      body.settings_page_hNews tr input.button {
        width: auto;
        margin: 0 2em;
      }

      body.settings_page_hNews #hnews_map {
        width: 90%;
        max-width: 650px;
      }
    </style>
<?php
  }

  /**
   * Add the custom Javascript we need to render our meta boxes and register
   * our hNews javascript file.
   */
  function add_js() {
    echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
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
    foreach ($this->supported_fields_main + $this->supported_fields_org as $k => $v) {
      if (empty($post->{"hnews_$k"}) && empty($post->ID))
        $$k = $options[$k];
      elseif ( ! empty($post->{"hnews_$k"})) {
        $$k = $post->{"hnews_$k"};
      }
    }

    $principles_url = empty($principles_url) ? 'http://' : $principles_url;
    $license_url = empty($license_url) ? 'http://' : $license_url;
    $url = empty($url) ? 'http://' : $url;

    ?>
    <fieldset id="hnews_principles">
      <legend><?php _e('Principles') ?></legend>
      <div>
        <label for="hnews_principles_url"><?php _e('Principles URL:') ?></label>
        <p class="howto"><?php _e('This is the URL where your statement of principles can be found.'); ?></p>
        <input name="hnews_principles_url" type="text" class="code url" id="hnews_principles_url" value="<?php echo esc_attr($principles_url); ?>" /><br />
        <p class="howto"><?php _e('see <a href="http://en.wikipedia.org/wiki/journalism_ethics" rel="external">http://en.wikipedia.org/wiki/journalism_ethics</a>'); ?></p>
      </div>

      <div>
        <label for="hnews_principles_text"><?php _e('Principles label:') ?></label>
        <input name="hnews_principles_text" type="text" id="hnews_principles_text" value="<?php echo esc_attr($principles_text); ?>" />
      </div>
    </fieldset>

    <fieldset id="hnews_license">
      <legend><?php _e('License') ?></legend>
      <div>
        <label for="hnews_license_url"><?php _e('License URL:') ?></label>
        <p class="howto"><?php _e('This is the URL where the license associated with your article can be found.'); ?></p>
        <input name="hnews_license_url" type="text" class="code url" id="hnews_license_url" value="<?php echo esc_attr($license_url); ?>" />
      </div>

      <div>
        <label for="hnews_license_text"><?php _e('License name:') ?></label>
        <input name="hnews_license_text" type="text" id="hnews_license_text" value="<?php echo esc_attr($license_text); ?>" />
        <p class="howto"><?php _e('e.g. Creative Commons Attribution-Non-Commercial-Share Alike 3.0'); ?></p>
      </div>
    </fieldset>

    <fieldset id="hnews_source_org">
      <legend><?php _e('Source Organisation') ?></legend>
      <p><?php __('This is the original publisher of the article.') ?></p>

      <div id="hnews_org_basic">
        <div>
          <label for="hnews_org_name"><?php _e('Organization Name:') ?></label>
          <input name="hnews_org_name" type="text" id="hnews_org_name" value="<?php echo esc_attr($org_name); ?>" />
        </div>

        <div>
          <label for="hnews_url"><?php _e('URL:') ?></label>
          <input name="hnews_url" type="text" class="code" id="hnews_url" value="<?php echo esc_attr($url); ?>" />
        </div>
      </div>

      <div id="hnews_org_more">
        <div id="hnews_org_other">
          <div>
            <label for="hnews_org_unit"><?php _e('Organization Unit:') ?></label>
            <input name="hnews_org_unit" type="text" id="hnews_org_unit" value="<?php echo esc_attr($org_unit); ?>" />
          </div>

          <div>
            <label for="hnews_email"><?php _e('Email:') ?></label>
            <input name="hnews_email" type="text" class="code" id="hnews_email" value="<?php echo esc_attr($email); ?>" />
          </div>

          <div>
            <label for="hnews_tel"><?php _e('Phone:') ?></label>
            <input name="hnews_tel" type="text" id="hnews_tel" value="<?php echo esc_attr($tel); ?>" />
          </div>
        </div>

        <div id="hnews_org_address">
          <div>
            <label for="hnews_post_office_box"><?php _e('PO Box:') ?></label>
            <input name="hnews_post_office_box" type="text" id="hnews_post_office_box" value="<?php echo esc_attr($post_office_box); ?>" />
          </div>

          <div>
            <label for="hnews_extended_address"><?php _e('<abbr title="apartment">Apt</abbr>/Suite Number:') ?></label>
            <input name="hnews_extended_address" type="text" id="hnews_extended_address" value="<?php echo esc_attr($extended_address); ?>" />
          </div>

          <div>
            <label for="hnews_street_address"><?php _e('Street Address:') ?></label>
            <input name="hnews_street_address" type="text" id="hnews_street_address" value="<?php echo esc_attr($street_address); ?>" />
          </div>

          <div>
            <label for="hnews_locality"><?php _e('City/Town:') ?></label>
            <input name="hnews_locality" type="text" id="hnews_locality" value="<?php echo esc_attr($locality); ?>" />
          </div>

          <div>
            <label for="hnews_region"><?php _e('State/County:') ?></label>
            <input name="hnews_region" type="text" id="hnews_region" value="<?php echo esc_attr($region); ?>" />
          </div>

          <div>
            <label for="hnews_postal_code"><?php _e('Zip/Postal Code:') ?></label>
            <input name="hnews_postal_code" type="text" id="hnews_postal_code" value="<?php echo esc_attr($postal_code); ?>" />
          </div>

          <div>
            <label for="hnews_country_name"><?php _e('Country:') ?></label>
            <input name="hnews_country_name" type="text" id="hnews_country_name" value="<?php echo esc_attr($country_name); ?>" />
          </div>
        </div>
      </div>
    </fieldset>
<?php
  }

  /**
   * The hNews Geolocation meta box rendering function
   */
  function meta_box_geo($post) {
    // fallback for when the filter for post_to_edit isn't present
    if ( ! isset($post->hnews_geo_latitude)) {
      $this->post_to_edit($post);
    }
    $options = get_option('hnews_options');
    foreach ($this->supported_fields_geo as $k => $v) {
      if (empty($post->{"hnews_$k"}) && empty($post->ID))
        $$k = $options[$k];
      elseif ( ! empty($post->{"hnews_$k"})) {
        $$k = $post->{"hnews_$k"};
      }
    } ?>
    <fieldset>
      <div>
        <label for="hnews_geo_latitude"><?php _e('Latitude:') ?></label>
        <input name="hnews_geo_latitude" type="text" size="13" id="hnews_geo_latitude" value="<?php echo esc_attr($geo_latitude); ?>" />
      </div>
      <div>
        <label for="hnews_geo_longitude"><?php _e('Longitude:') ?></label>
        <input name="hnews_geo_longitude" type="text" size="13" id="hnews_geo_longitude" value="<?php echo esc_attr($geo_longitude); ?>" />
      </div>
      <div id="geo_addr_wrap" class="hide-if-no-js">
        <label class="screen-reader-text" for="geo_addr"><?php _e('Address:') ?></label>
        <div id="geo_addrhint"><?php _e('Address to lookup') ?></div>
        <input type="text" id="geo_addr" name="geo_addr" class="form-input-tip" size="16" autocomplete="off" value="">
        <input type="button" class="button geo_addr" value="Find" tabindex="3">
      </div>
    </fieldset>
<?php
  }

  /**
   * Add our extra fields to the post being edited
   */
  function post_to_edit($post) {
    $id = $post->ID;
    foreach ($this->supported_fields_geo + $this->supported_fields_main + $this->supported_fields_org as $k => $v) {
      $post->{"hnews_$k"} = get_post_meta($id, "_hnews_$k", true );
    }
    return $post;
  }

  /**
   * Process all post results and add the custom fields
   */
  function posts_results($posts) {
    foreach ($posts as $k => $post) {
      $posts[$k] = $this->post_to_edit($post);
    }
    return $posts;
  }

  /**
   * The hNews admin options rendering function
   */
  function hnews_options_page() { ?>
    <div class="wrap">
      <h2><?php _e('hNews Defaults'); ?></h2>
      <form method="post" action="options.php">
<?php settings_fields('hnews_options');
      do_settings_sections('hnews_page');
?>
        <table class="form-table" class="hide-if-no-js">
          <tbody>
            <tr valign="top">
              <th scope="row">
                <label for="geo_addr"><?php _e('Address to lookup') ?></label>
              </th>
              <td>
                <input type="text" id="geo_addr" name="geo_addr" size="16" autocomplete="off" value="">
                <input type="button" class="button geo_addr" value="Find">
              </td>
            </tr>
          </tbody>
        </table>

        <p class="submit">
          <input type="submit" name="Submit" class="button-primary" value="Save Changes">
        </p>
      </form>
    </div>
<?php
  }
}

include 'template_functions.php';
new hNews();
?>