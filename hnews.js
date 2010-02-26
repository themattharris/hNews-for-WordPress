var hnews_license = function(id) {
  var that = this;

  if (document.getElementById(id)) {
    id = '#'+id;

    var set_name = function(id, name) {
      jQuery(id).val(name);
    };

    jQuery(id).blur(function(event){
      jQuery.get('admin-ajax.php?action=hnews&hnews_url='+escape(jQuery(this).val()), function(data) {
        set_name(id.replace('url', 'text'), data);
      })
    });
  }
};

var hnews_hint = function(id, map) {
  var show = function() {
    jQuery(id+'hint').hide();
    if (jQuery(id).val() == '') {
      jQuery(id).val(jQuery(id+'hint').text());
    }
  },

  hide = function() {
    if (jQuery(id).val() == jQuery(id+'hint').text()) {
      jQuery(id).val('');
    }
  };

  jQuery(id).focus(function() {
    hide();
  }).blur(function() {
    show();
  }).keydown(function(event) {
    if (event.which == 13) { // enter
      map.geocode();
      return false;
    }
  });

  jQuery('input.'+id.replace('#','')).click(function() {
    map.geocode();
  });

  show();
}

var hnews_map = function(id, geocoder_id, div_id) {
  var map, marker, geocoder,
      that = this;

  var update = function(loc) {
    move_to(loc);
    update_marker(loc);
    update_textboxes(loc);
  },

  update_marker = function(loc) {
    if (marker === undefined) {
      marker = new google.maps.Marker({
        position: loc,
        map: map
      });
    } else {
      marker.setPosition(loc);
    }
  },

  move_to = function(loc) {
    map.setCenter(loc);
  },

  update_textboxes = function(loc) {
    lat = loc.lat().toFixed(4);
    lng = loc.lng().toFixed(4);

    jQuery(id+'_latitude').val(lat);
    jQuery(id+'_longitude').val(lng);
  };

  if (jQuery(id+' fieldset').length) {
    jQuery(id+' fieldset').before('<div id="'+div_id+'"></div>');
  } else if (jQuery('tr '+id+'_latitude').length) {
    jQuery('tr '+id+'_latitude').closest('table').before('<div id="'+div_id+'"></div>');
  } else {
    return;
  }

  lat = jQuery(id+'_latitude').val() || 37.4419;
  lng = jQuery(id+'_longitude').val() || -122.1419;
  loc = new google.maps.LatLng(lat, lng);

  map = new google.maps.Map(
    document.getElementById(div_id), {
      zoom: 10,
      center: loc,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    });

  google.maps.event.addListener(map, 'click', function(event) {
    return update(event.latLng);
  });

  if (jQuery(id+'_latitude').val() && jQuery(id+'_longitude').val()) {
    update_marker(loc);
  }

  return {
    geocode : function(address) {
      address = ( ! address) ? jQuery(geocoder_id).val() : address;
      geocoder = new google.maps.Geocoder();
      geocoder.geocode({
          'address': address
      },
      function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          update(results[0].geometry.location);
          map.setZoom(15);
        } else {
          alert("Could not find that address because: " + status);
        }
      });
    }
  };
}

var hnews_showmore = function(id, text) {
  var hintid = id+'_hint';
      content = '<a id="'+hintid+'" href="#'+id+'">+ '+text+'</a>';

  id = '#'+id;

  var replace_text = function(a, b) {
    jQuery('#'+hintid).text(
      jQuery('#'+hintid).text().replace(a, b)
    );
  },
  toggle = function() {
    if (jQuery(id).is(':visible')) {
      jQuery(id).hide();
      replace_text('-', '+');
    } else {
      jQuery(id).show();
      replace_text('+', '-');
    }
  };

  if (jQuery(id).length) {
    jQuery(id).before(content)
      .hide();
    jQuery('#'+hintid).click(function(event) {
      event.preventDefault();
      toggle();
      return false;
    });
  }
}

jQuery(function($){
  geomap = new hnews_map('#hnews_geo', '#geo_addr', 'hnews_map');
  geohint = new hnews_hint('#geo_addr', geomap);
  license_url = new hnews_license('hnews_license_url');
  principles_url = new hnews_license('hnews_principles_url');

  jQuery('#hnews_org_unit').parents('table').attr('id', 'hnews_org_more');
  togglr = new hnews_showmore('hnews_org_more', 'Add more information about source organization');
});