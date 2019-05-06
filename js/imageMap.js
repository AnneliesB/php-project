//Access token
//Restricted in MapBox account by URL
mapboxgl.accessToken = 'pk.eyJ1IjoiYXJuaWk1IiwiYSI6ImNqdmNjMW5lZTFnenQ0NG15YThkNnB6aXoifQ.6YSNEi0JUyLhDkYg2CQLgQ';

// Create fullscreen imageMap
//set height of the map to the available height of the viewport minus the navbar
var mapContainer = document.querySelector("#map");
var navbar = document.querySelector(".navbar");
navbar.style.marginBottom = "0px";
var navHeight = navbar.offsetHeight;
mapContainer.style.height = 'calc( 100vh - ' + navHeight + 'px)';


//Init map
var map = new mapboxgl.Map({
    container: 'map',
    center: [3.26792783, 50.8546051], //NOTE: LNG and then LAT -> inverted of what we are used to!
    zoom: 9,
    style: "mapbox://styles/mapbox/light-v9"
    
  });


//Get posts location data (lat/lng) and image from DB via AJAX call
axios.get('ajax/imageMap.php',{
    
})

//Response
.then(function (response) {

    //Put data from response into a variable and then call addmarkers function
    let postsJSON = response.data;
    addMarkers(postsJSON);

})

//Catch error
.catch(function (error) {
    console.log(error);
});


//Add markers to map
function addMarkers(postsJSON){

    postsJSON.forEach(function(marker) {
        
        //Create a DOM element for the marker
        var el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url(images/' + marker.url_cropped + ')';
         
        //Add marker to map
        new mapboxgl.Marker(el)
        .setLngLat([marker.lng , marker.lat])
        .addTo(map);

    });

}
