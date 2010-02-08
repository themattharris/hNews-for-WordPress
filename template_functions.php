<?php

// Geo
function the_hnews_geo($before='', $after='', $echo=true) {
  $geo = get_the_hnews_geo();

  if (strlen($geo) == 0)
    return;

  $geo = $before . $geo . $after;

  if ($echo)
    echo $geo;
  else
    return $geo;
}
function get_the_hnews_geo($id = 0) {
  $post = &get_post($id);

  $lat = $post->hnews_geo_latitude;
  $lng = $post->hnews_geo_longitude;

  $geo = '';
  $geo .= strlen($lat) == 0 ? '' : "<span>Lat: $lat</span>";
  $geo .= strlen($lng) == 0 ? '' : "<span>Long: $lng</span>";

  return apply_filters('the_geo', $geo, $post->ID);
}

// Source Organisation
function the_source_org($before='', $after='', $echo=true) {
  $org = get_the_source_org();

  if (strlen($org) == 0)
    return;

  $org = $before . $org . $after;

  if ($echo)
    echo $org;
  else
    return $org;
}
function get_the_source_org($id = 0) {
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

  return apply_filters('the_source_org', $org, $post->ID);
}

// Principles
function the_principles_url($before='', $after='', $echo=true) {
  $url = get_the_principles_url();

  if (strlen($url) == 0)
    return;

  $url = $before . $url . $after;

  if ($echo)
    echo $url;
  else
    return $url;
}
function get_the_principles_url($id = 0) {
  $post = &get_post($id);
  if (empty($post->hnews_principles_url))
    return '';

  $url = '<a href="' . $post->hnews_principles_url . '" rel="principles">Principles</a>';
  return apply_filters('the_principles_url', $url, $post->ID);
}

// License URL
function the_license_url($before='', $after='', $echo=true) {
  $url = get_the_license_url();

  if (strlen($url) == 0)
    return;

  $url = $before . $url . $after;

  if ($echo)
    echo $url;
  else
    return $url;
}
function get_the_license_url($id = 0) {
  $post = &get_post($id);
  if (empty($post->hnews_license_url))
    return '';

  $name = strlen($post->hnews_license_text) == 0 ? $post->hnews_license_url : $post->hnews_license_text;
  $url = '<a href="' . $post->hnews_license_url . '" rel="license">' . $post->hnews_license_text . '</a>';
  return apply_filters('the_license_url', $url, $post->ID);
}

?>