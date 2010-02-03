<?php

function the_geo($before = '', $after = '', $echo = true) {
  $geo = get_the_geo();

  if (strlen($geo) == 0)
    return;

  $geo = $before . $geo . $after;

  if ($echo)
    echo $geo;
  else
    return $geo;
}

function get_the_geo($id = 0) {
  $post = &get_post($id);

  $lat = $post->geo_latitude;
  $lng = $post->geo_longitude;

  $geo = "location: $lat,$lng";

  return apply_filters('the_geo', $geo, $post->ID);
}


?>