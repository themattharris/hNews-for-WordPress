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
