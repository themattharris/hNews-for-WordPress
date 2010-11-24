<?php

// Geo
function the_hnews_geo($before='', $after='', $echo=true, $lattxt='latitude ', $lngtxt='longitude ', $seperator=' and ') {
  $geo = get_the_hnews_geo(0, $lattxt, $lngtxt, $seperator);

  if (strlen($geo) > 0)
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

  $geo = '';
  if( strlen($lat)>0 && strlen($lng)>0 ) {
    $geo = "$lattxt<span class=\"latitude\">$lat</span>";
    $geo .= $seperator;
    $geo .= "$lngtxt<span class=\"longitude\">$lng</span>";
  }

  return apply_filters('the_hnews_geo', $geo, $post->ID);
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
      $org = "<a href=\"$url\" class=\"fn org url\">$org_name</a>";
    } else {
      $org = "<span class=\"fn org\">$org_name</span>";
    }
  } elseif ( ! empty($org_unit)) {
    $org .= '<span class="fn">';
    $org .= "<span class=\"organization-name\">$org_name</span>";
    $org .= $seperator;
    $org .= "<span class=\"organization-unit\">$org_unit</span>";
    $org .= '</span>';
  }

  $adr_bits = array();
  if ( ! empty($post_office_box)) $adr_bits[] = "<span class=\"post-office-box\">PO Box $post_office_box</span>";
  if ( ! empty($extended_address)) $adr_bits[] = "<span class=\"extended-address\">$extended_address</span>";
  if ( ! empty($street_address)) $adr_bits[] = "<span class=\"street-address\">$street_address</span>";
  if ( ! empty($locality)) $adr_bits[] = "<span class=\"locality\">$locality</span>";
  if ( ! empty($region)) $adr_bits[] = "<span class=\"region\">$region</span>";
  if ( ! empty($postal_code)) $adr_bits[] = "<span class=\"postal-code\">$postal_code</span>";
  if ( ! empty($country_name)) $adr_bits[] = "<span class=\"country-name\">$country_name</span>";
  if ( ! empty($adr_bits)) {
    $adr = '<span class="adr">'.implode($seperator, $adr_bits).'</span>';
  } else {
    $adr = '';
  }

  $tel_bits = array();
  if ( ! empty($tel)) $tel_bits[] = "<span class=\"tel\">$tel</span>";
  if ( ! empty($email)) $tel_bits[] = "<a href=\"mailto:$email\" class=\"email\">$email</a>";
  $tel = implode($seperator, $tel_bits);

  $frags = array();
  if( $org ) $frags[] = $org;
  if( $adr ) $frags[] = $adr;
  if( $tel ) $frags[] = $tel;

  return apply_filters('the_hnews_source_org', implode( $seperator, $frags ), $post->ID);
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
  $url = '<a href="' . $post->hnews_license_url . '" rel="item-license">' . $name . '</a>';
  return apply_filters('the_hnews_license_url', $url, $post->ID);
}

/**
 * Produces an example hNews meta block
 *
 * @return the hNews meta block in HTML
 */
function hnews_meta($format='l jS F Y \a\t Hi T') {
?>
  <!-- hNews meta -->
	<p class="postmetadata alt hnewsmeta">
	  <small>
<?php $geo = the_hnews_geo(' at <span class="geo">', '</span>', false);
      if ( get_the_author_meta('url') ) {
    		$author = '<span class="vcard"><a href="' . get_the_author_meta('url') . '" title="' . esc_attr( sprintf(__("Visit %s&#8217;s website"), get_the_author()) ) . '" class="fn url" rel="external">' . get_the_author() . '</a></span>';
    	} else {
    		$author = get_the_author();
    	} ?>
	    Written by <?php echo $author; echo $geo; ?>.
<?php the_hnews_source_org('<span class="source-org vcard">Published by ', "</span>. "); ?>
        <span class="published">Published on <span class="value-title" title="<?= get_the_time('c') ?> "><?= get_the_time($format) ?>.</span>
<?php if( get_the_time('c') != get_the_modified_date('c') ) { ?>
        <span class="updated">Updated on <span class="value-title" title="<?= get_the_modified_date('c') ?> "><?= get_the_modified_date($format) ?>.</span>
<?php } ?>
<?php the_hnews_principles_url('Published under ', '. ' ); ?>
<?php the_hnews_license_url(' Licensed under ', '. ' ); ?>
		</small>
	</p>
<?php
}
?>
