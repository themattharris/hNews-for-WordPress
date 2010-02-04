<div>
  <h2><?php _e('hNews Defaults'); ?></h2>
  <form method="post" action="options.php">
<?php settings_fields('hnews_options');
      do_settings_sections('hnews_page'); 
?>
    <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="Save Changes">
    </p>
  </form>
</div>
