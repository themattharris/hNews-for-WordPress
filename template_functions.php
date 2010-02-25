<?php

// Geo
function the_hnews_geo($before='', $after='', $echo=true, $lattxt='latitude ', $lngtxt='longitude ', $seperator=' and ') {
  $geo = get_the_hnews_geo(0, $lattxt, $lngtxt, $seperator);

  if (strlen($geo) == 0)
    return;

  $geo = $before . $geo . $after;

  if ($echo)
    echo $geo;
  else
    return $geo;
}
function get_the_hnews_geo($id = 0, $lattxt, $lngtxt, $seperator) {
  $post = &get_post($id);

  $lat = $post->hnews_geo_latitude;
  $lng = $post->hnews_geo_longitude;

  $geo[] = strlen($lat) == 0 ? '' : "$lattxt<span class=\"latitude\">$lat</span>";
  $geo[] = strlen($lng) == 0 ? '' : "$lngtxt<span class=\"longitude\">$lng</span>";

  return apply_filters('the_hnews_geo', trim(implode($seperator, $geo)), $post->ID);
}

// Source Organisation
function the_hnews_source_org($before='', $after='', $echo=true) {
  $org = get_the_hnews_source_org();

  if (strlen($org) == 0)
    return;

  $org = $before . $org . $after;

  if ($echo)
    echo $org;
  else
    return $org;
}
function get_the_hnews_source_org($id = 0, $seperator=', ') {
  $post = &get_post($id);

  $org_name = $post->hnews_org_name;
  $org_unit = $post->hnews_org_unit;
  $email = $post->hnews_email;
  $url = $post->hnews_url;
  $tel = $post->hnews_tel;
  $post_office_box = $post->hnews_post_office_box;
  $extended_address = $post->hnews_extended_address;
  $street_address = $post->hnews_street_address;
  $locality = $post->hnews_locality;
  $region = $post->hnews_region;
  $postal_code = $post->hnews_postal_code;
  $country_name = $post->hnews_country_name;

  $org = '';
  if (empty($org_unit) && ! empty($org_name)) {
    if ( ! empty($url)) {
      $org = "<a href=\"$url\" class=\"fn org url\">$org_name</a>, ";
    } else {
      $org = "<span class=\"fn org\">$org_name</span>, ";
    }
  } elseif ( ! empty($org_unit)) {
    $org .= '<span class="fn">';
    $org .= "<span class=\"organization-name\">$org_name</span>";
    $org .= "<span class=\"organization-unit\">$org_unit</span>";
    $org .= '</span>';
  }

  $adr = array();
  if ( ! empty($post_office_box)) $adr[] = "<span class=\"post-office-box\">PO Box $post_office_box</span>";
  if ( ! empty($extended_address)) $adr[] = "<span class=\"extended-address\">$extended_address</span>";
  if ( ! empty($street_address)) $adr[] = "<span class=\"street-address\">$street_address</span>";
  if ( ! empty($locality)) $adr[] = "<span class=\"locality\">$locality</span>";
  if ( ! empty($region)) $adr[] = "<span class=\"region\">$region</span>";
  if ( ! empty($postal_code)) $adr[] = "<span class=\"postal-code\">$postal_code</span>";
  if ( ! empty($country_name)) $adr[] = "<span class=\"country-name\">$country_name</span>";
  if ( ! empty($adr)) {
    $adr = '<span class="adr">'.implode($seperator, $adr).'</span>, ';
  }

  $tel = array();
  if ( ! empty($tel)) $tel[] = "<span class=\"tel\">$tel</span>";
  if ( ! empty($email)) $tel[] = "<a href=\"mailto:$email\" class=\"email\">$email</a>";
  $tel = implode($seperator, $tel);

  return apply_filters('the_hnews_source_org', $org.$adr.$tel, $post->ID);
}

// Principles
function the_hnews_principles_url($before='', $after='', $echo=true) {
  $url = get_the_hnews_principles_url();

  if (strlen($url) == 0)
    return;

  $url = $before . $url . $after;

  if ($echo)
    echo $url;
  else
    return $url;
}
function get_the_hnews_principles_url($id = 0) {
  $post = &get_post($id);
  if (empty($post->hnews_principles_url))
    return '';

  $name = strlen($post->hnews_principles_text) == 0 ? $post->hnews_principles_url : $post->hnews_principles_text;
  $url = '<a href="' . $post->hnews_principles_url . '" rel="principles">' . $name . '</a>';
  return apply_filters('the_hnews_principles_url', $url, $post->ID);
}

// License URL
function the_hnews_license_url($before='', $after='', $echo=true) {
  $url = get_the_hnews_license_url();

  if (strlen($url) == 0)
    return;

  $url = $before . $url . $after;

  if ($echo)
    echo $url;
  else
    return $url;
}
function get_the_hnews_license_url($id = 0) {
  $post = &get_post($id);
  if (empty($post->hnews_license_url))
    return '';

  $name = strlen($post->hnews_license_text) == 0 ? $post->hnews_license_url : $post->hnews_license_text;
  $url = '<a href="' . $post->hnews_license_url . '" rel="license">' . $name . '</a>';
  return apply_filters('the_hnews_license_url', $url, $post->ID);
}

/**
 * Produces an example hNews meta block
 *
 * @return the hNews meta block in HTML
 */
function hnews_meta($format='l jS F Y \a\t Hi T') { ?>
  <!-- hNews meta -->
	<p class="postmetadata alt hnewsmeta">
	  <small>
<?php $geo = the_hnews_geo(' at <span class="geo">', '</span>', false); ?>
	    Written by <?php the_author(); echo $geo; ?>.
<?php $time = get_the_time($format);
	    the_hnews_source_org(' First published by <span class="vcard">', "</span> on $time."); ?>
<?php $principles = the_hnews_principles_url(' and published under ', '', false);
	    the_hnews_license_url(' Licensed as ', "$principles."); ?>
<?php $mod = get_the_modified_date($format);
      if ($mod !== $time):
        _e(" Updated on $mod.");
      endif; ?>

		</small>
	</p>
<?php
}
?>