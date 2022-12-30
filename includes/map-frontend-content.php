<?php defined( 'ABSPATH' ) or die();
$id  = $atts['id'];
$lat = $atts['lat'];
$log = $atts['log'];

?>


<script type="text/javascript"
        src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
<script type="text/javascript"
        src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
<script type="text/javascript"
        src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
<script type="text/javascript"
        src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
<link rel="stylesheet" type="text/css"
      href="https://js.api.here.com/v3/3.1/mapsjs-ui.css"/>
<script>window.ENV_VARIABLE = 'developer.here.com'</script>
<div class='button_container'>
    <button onClick="myfunction()">
        Random Map
    </button>
</div>
<div style="width: 300px; height: 480px ; text-align: center" id="mapContainer">
    <br/>

</div>
<div style="width: 300px; height: 480px ; text-align: center"
     id="mapContainerRandom">
    <br/>

</div>

<script>
    var lat = "<?php echo " $lat "?>";
    var log = "<?php echo " $log "?>";
    console.log(lat, log);


    // Initialize the platform object
    var platform = new H.service.Platform({
        'apikey': '0ddChwxReJv6n1utD2-wxnX19jDfzwCJH5GvFtEdMWI'
    });

    // Obtain the default map types from the platform object
    var maptypes = platform.createDefaultLayers();

    // Instantiate (and display) the map
    var map = new H.Map(
        document.getElementById('mapContainer'),
        maptypes.vector.normal.map,
        {
            zoom: 10,
            center: {lat: lat, lng: log}
        });


    function Random_map(min, max, decimals) {
        //alert('hi');
        const str = (Math.random() * (max - min) + min).toFixed(decimals);
        return parseFloat(str);
    }

    function myfunction() {
        var lat_min = 1;
        var lat_max = 28.3974;
        var log_min = 1;
        var log_max = 84.1258;
        var decimals = 3;
        document.getElementById('mapContainer').style.display = 'none';

        var lat_number = Random_map(lat_min, lat_max, decimals);
        var log_number = Random_map(log_min, log_max, decimals);
        console.log(lat_number)
        console.log(log_number)
        // return [lat_number,log_number];
        var newmap = new H.Map(
            document.getElementById('mapContainerRandom'),
            maptypes.vector.normal.map,
            {
                zoom: 10,
                center: {lng: log_number, lat: lat_number}
            });
    }
</script>