//Access token
//Restricted in MapBox account by URL
mapboxgl.accessToken = 'pk.eyJ1IjoiYXJuaWk1IiwiYSI6ImNqdmNjMW5lZTFnenQ0NG15YThkNnB6aXoifQ.6YSNEi0JUyLhDkYg2CQLgQ';

// Create fullscreen imageMap
//set height of the map to the available height of the viewport minus the navbar
const mapContainer = document.querySelector("#map");
const navbar = document.querySelector(".navbar");
navbar.style.marginBottom = "0px";
let navHeight = navbar.offsetHeight;
mapContainer.style.height = 'calc( 100vh - ' + navHeight + 'px)';


//Init map
let map = new mapboxgl.Map({
    container: 'map',
    center: [3.26792783, 50.8546051], //NOTE: LNG and then LAT -> inverted of what we are used to!
    zoom: 9,
    style: "mapbox://styles/mapbox/light-v9"
    
  });

  //create a global currentMarkers variable that will store all added markers
  let currentMarkers = [];


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
        let el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url(images/' + marker.url_cropped + ')';
         
        //Add marker to map
        singleMarker = new mapboxgl.Marker(el)
        .setLngLat([marker.lng , marker.lat])
        .addTo(map);
        currentMarkers.push(singleMarker);

    });

}


//listen to search field enter or click
let searchInput = document.querySelector("#searchMapInput");
let searchBtn = document.querySelector("#searchMapBtn");

searchInput.addEventListener("keypress", function(e){
    //check for enter key
    let key = e.which || e.keyCode;
    if (key === 13){

        //enter key has been pressed
        //get value from input field
        let query = searchInput.value;
        console.log(query);

        //update all markers
        updateMarkers(query);


        //Prevent default action to happen on entering
        e.preventDefault();
    }
    
});

searchBtn.addEventListener("click", function(e){
    //search btn has been clicked
    //get value from input field
    let query = searchInput.value;
    console.log(query);

    //update all markers
    updateMarkers(query);

    //Prevent default action to happen on clicking button
    e.preventDefault();
});


//update all markers on the map
function updateMarkers(query){

    //delete all current markers, if there are any
    if (currentMarkers !== null) {
        for (let i = 0; i < currentMarkers.length; i++) {

            //remove marker
            currentMarkers[i].remove();
        }
        //reset currentMarkers array after removing the markers on the map
        currentMarkers = [];
    }
    

    //get all posts back that contain the query in the description
    axios.post('ajax/imageMapSearch.php',{
        query: query
    })
    
    //Response
    .then(function (response) {
    
        //Put data from response into a variable and then call addmarkers function
        console.log(response);
        let postsJSON = response.data;
        console.log(postsJSON);
        addMarkers(postsJSON);
    
    })
    
    //Catch error
    .catch(function (error) {
        console.log(error);
    });

}
