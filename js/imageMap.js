//access token
mapboxgl.accessToken = 'pk.eyJ1IjoiYXJuaWk1IiwiYSI6ImNqdmJnN3RsMDB1cHQzenFta21vNmRldDgifQ.Kfk5NHGXQX4uMOSH5abrDg';

//init map
var map = new mapboxgl.Map({
    container: 'map',
    center: [3.26792783, 50.8546051], //NOTE: LNG and then LAT -> inverted of what we are used to!
    zoom: 8,
    style: "mapbox://styles/mapbox/light-v9"
    
  });