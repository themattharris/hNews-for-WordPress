<fieldset>
  <div>
    <label for="geo_latitude"><?php _e('Latitude:') ?></label>
    <input name="geo_latitude" type="text" size="13" id="geo_latitude" value="<?php echo esc_attr( $post->geo_latitude ); ?>" />
  </div>
  <div>
    <label for="geo_longitude"><?php _e('Longitude:') ?></label>
    <input name="geo_longitude" type="text" size="13" id="geo_longitude" value="<?php echo esc_attr( $post->geo_longitude ); ?>" />
  </div>
  <div id="geo_addr_wrap" class="hide-if-no-js">
    <label class="screen-reader-text" for="geo_addr"><?php _e('Address:') ?></label>
    <div id="geo_addrhint"><?php _e('Address to lookup') ?></div>
    <input type="text" id="geo_addr" name="geo_addr" class="form-input-tip" size="16" autocomplete="off" value="">
    <input type="button" class="button geo_addr" value="Find" tabindex="3">
  </div>
</fieldset>