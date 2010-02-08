<fieldset id="hnews_principles">
  <legend><?php _e('Principles') ?></legend>
  <div>
    <label for="hnews_principles_url"><?php _e('Principles URL:') ?></label>
    <input name="hnews_principles_url" type="text" class="code" id="hnews_principles_url" value="<?php echo esc_attr($principles_url); ?>" /><br />
    <p class="howto"><?php _e('This is the URL where your statement of principles can be found.'); ?></p>
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
    <input name="hnews_license_url" type="text" class="code" id="hnews_license_url" value="<?php echo esc_attr($license_url); ?>" />
  </div>

  <div>
    <label for="hnews_license_text"><?php _e('License name:') ?></label>
    <input name="hnews_license_text" type="text" id="hnews_license_text" value="<?php echo esc_attr($license_text); ?>" />
  </div>
</fieldset>

<fieldset id="hnews_source_org">
  <legend><?php _e('Source Organisation') ?></legend>
  <p><?php __('All fields are optional.') ?></p>

  <div id="hnews_org_basic">
    <div>
      <label for="hnews_org_name"><?php _e('Organization Name:') ?></label>
      <input name="hnews_org_name" type="text" id="hnews_org_name" value="<?php echo esc_attr($org_name); ?>" />
    </div>

    <div>
      <label for="hnews_org_unit"><?php _e('Organization Unit:') ?></label>
      <input name="hnews_org_unit" type="text" id="hnews_org_unit" value="<?php echo esc_attr($org_unit); ?>" />
    </div>

    <div>
      <label for="hnews_email"><?php _e('Email:') ?></label>
      <input name="hnews_email" type="text" class="code" id="hnews_email" value="<?php echo esc_attr($email); ?>" />
    </div>

    <div>
      <label for="hnews_url"><?php _e('URL:') ?></label>
      <input name="hnews_url" type="text" class="code" id="hnews_url" value="<?php echo esc_attr($url); ?>" />
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
      <label for="hnews_extended_address"><?php _e('Apartment/Suite Number:') ?></label>
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
</fieldset>