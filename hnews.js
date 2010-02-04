var hnews, hnews_map, hnews_marker;

(function($){
  hnews = {
    init : function(args) {
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
        hnews.geocode($('#geo_addr').val());
      });
    },

    update : function(loc) {
      hnews.move_to(loc);
      hnews.update_marker(loc);
      hnews.update_textboxes(loc);
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

jQuery(document).ready(function($){
  hnews.init();
});