
var map;
var markers = [];
function initMap(adderess_lat = "",address_long = "") 
{
  var options = {
    types: ['(cities)'],
    componentRestrictions: {country: "in"}
   };
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.Autocomplete(input, options);
  google.maps.event.addListener(searchBox, 'place_changed', function () {
      var place = searchBox.getPlace();
      if (place.length == 0) {
        return;
      }
      console.log(place.formatted_address);
      var address = place.formatted_address;
      $('#name').val(place.name);
      $('#address_latitude').val(place.geometry.location.lat());
      $('#address_longitude').val(place.geometry.location.lng());
  });
}


