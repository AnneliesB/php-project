//access token
mapboxgl.accessToken = 'pk.eyJ1IjoiYXJuaWk1IiwiYSI6ImNqdmJnN3RsMDB1cHQzenFta21vNmRldDgifQ.Kfk5NHGXQX4uMOSH5abrDg';

//init map
var map = new mapboxgl.Map({
    container: 'map',
    center: [3.26792783, 50.8546051], //NOTE: LNG and then LAT -> inverted of what we are used to!
    zoom: 8,
    style: "mapbox://styles/mapbox/light-v9"
    
  });


//JSON array of images
var postsJSON = {

    "posts": [
        {
            "lat": 50.924695711685304,
            "lng": 3.624462890625
        },
        {
            "lat": 50.97189158092897,
            "lng": 3.0158203125
        },
        {
            "lat": 50.85151823530889,
            "lng": 3.29223632812499
        }
    ]
};

// add markers to map
postsJSON.posts.forEach(function(marker) { //replace the postsJSON.posts with the acutal posts array
    // create a DOM element for the marker
    var el = document.createElement('div');
    el.className = 'marker';
    el.style.backgroundImage = 'url(images/0flipper.jpg)';
     
    // add marker to map
    new mapboxgl.Marker(el)
    .setLngLat([marker.lng , marker.lat])
    .addTo(map);
    });