var hnews, hnews_map, hnews_marker, hnews_license;

(function($){
  hnews_license = {
    init : function(args) {
      if ( ! $('#hnews_license_url').length) {
        return;
      }

      $('#hnews_license_url').blur(function(event){
        $.get('admin-ajax.php?action=hnews&hnews_url='+escape($(this).val()), function(data) {
          hnews_license.set_name(data);
        })
      });
    },

    set_name : function(name) {
      $('#hnews_license_text').val(name);
    },
  }

  hnews = {
    init : function(args) {
      if ( ! $('#hnews_geo fieldset').length) {
        return;
      }

      $('#hnews_geo fieldset').before('<div id="hnews_map"></div>');

      lat = $('#geo_latitude').val() || 37.4419;
      lng = $('#geo_longitude').val() || -122.1419;
      loc = new google.maps.LatLng(lat, lng);

      hnews_map = new google.maps.Map(
        document.getElementById("hnews_map"), {
          zoom: 10,
          center: loc,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

      google.maps.event.addListener(hnews_map, 'click', function(event) {
        return hnews.update(event.latLng);
      });

      if ($('#geo_latitude').val() && $('#geo_longitude').val()) {
        hnews.update_marker(loc);
      }

      $('input.geo_addr').click(function() {
        hnews.geocode();
      });

      $('#geo_addr').focus(function() {
        hnews.hide_hint();
      }).blur(function() {
        hnews.show_hint();
      }).keydown(function(event) {
        if (event.which == 13) { // enter
          hnews.geocode();
          return false;
        }
      });
      hnews.show_hint();
    },

    update : function(loc) {
      hnews.move_to(loc);
      hnews.update_marker(loc);
      hnews.update_textboxes(loc);
    },

    show_hint : function() {
      $('#geo_addrhint').hide();
      if ($('#geo_addr').val() == '') {
        $('#geo_addr').val($('#geo_addrhint').text());
      }
    },

    hide_hint : function() {
      if ($('#geo_addr').val() == $('#geo_addrhint').text()) {
        $('#geo_addr').val('');
      }
    },

    update_marker : function(loc) {
      if (hnews_marker === undefined) {
        hnews_marker = new google.maps.Marker({
          position: loc,
          map: hnews_map
        });
      } else {
        hnews_marker.setPosition(loc);
      }
    },

    move_to : function(loc) {
      hnews_map.setCenter(loc);
    },

    update_textboxes : function(loc) {
      lat = loc.lat().toFixed(4);
      lng = loc.lng().toFixed(4);

      $('#geo_latitude').val(lat);
      $('#geo_longitude').val(lng);
    },

    geocode : function(address) {
      address = ( ! address) ? $('#geo_addr').val() : address;
      geocoder = new google.maps.Geocoder();
      geocoder.geocode({
          'address': address
        },
        function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            hnews.update(results[0].geometry.location);
            hnews_map.setZoom(15);
          } else {
            alert("Could not find that address because: " + status);
          }
        });
    },
  }
})(jQuery);

jQuery(function($){
  hnews.init();
  hnews_license.init();
});