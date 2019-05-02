//Retrieving location where the post is uploaded
getMyLocation();


//get the current location with HTML5 geolocation
function getMyLocation(){
    navigator.geolocation.getCurrentPosition(position => {
        //save lat and lng
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;
        
        //turn lat and lng into a city address
        getCity(lat, lng);

    }, err => {
        //log errors if there are any
        console.log(err);
    } );
}

function getCity(lat, lng){
    //setup url with right coords and API KEY
    url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;

    //making API call with fetch of url
    fetch(url)

    .then(response => {
        //convert response to JSON object
        return response.json();
    })

    .then(json => {
        //retrieve city from JSON response
        let city = json.address.town;
        const inputCity = document.querySelector('#city');
        inputCity.value = city;
    });
}