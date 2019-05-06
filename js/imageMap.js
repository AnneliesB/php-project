//Access token
mapboxgl.accessToken = 'pk.eyJ1IjoiYXJuaWk1IiwiYSI6ImNqdmJnN3RsMDB1cHQzenFta21vNmRldDgifQ.Kfk5NHGXQX4uMOSH5abrDg';

//Init map
var map = new mapboxgl.Map({
    container: 'map',
    center: [3.26792783, 50.8546051], //NOTE: LNG and then LAT -> inverted of what we are used to!
    zoom: 8,
    style: "mapbox://styles/mapbox/light-v9"
    
  });


//Get posts location data (lat/lng) and image from DB via AJAX call
axios.post('ajax/imageMap.php',{
    
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
        console.log(marker);
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
