<fieldset id="hnews_principles">
  <legend><?php _e('Principles') ?></legend>
  <div>
    <label for="hnews_principles_url"><?php _e('Principles URL:') ?></label>
    <input name="hnews_principles_url" type="text" class="code" id="hnews_principles_url" value="<?php echo esc_attr( $post->hnews_principles_url ); ?>" /><br />
    (<?php _e('This is the URL where your statement of principles can be found.'); ?>)
  </div>
</fieldset>

<fieldset id="hnews_license">
  <legend><?php _e('License') ?></legend>
  <div>
    <label for="hnews_license_url"><?php _e('License URL:') ?></label>
    <input name="hnews_license_url" type="text" class="code" id="hnews_license_url" value="<?php echo esc_attr( $post->hnews_license_url ); ?>" /><br />
  </div>

  <div>
    <label for="hnews_license_text"><?php _e('License name:') ?></label>
    <input name="hnews_license_text" type="text" id="hnews_license_text" value="<?php echo esc_attr( $post->hnews_license_text ); ?>" /><br />
  </div>
</fieldset>