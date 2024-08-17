<div class="grid grid-cols-2">
    <div class="row">
        <div class="col-md-6">
            <p>Distance: {{ $distanceText }}</p>
            <p>Estimated Charge: ${{ $distance * 1000 }}</p>
        </div>
        <div class="col-md-6" id="map" style="height: 100vh;"></div>
    </div>
    
</div>

<script>
    function initMap() {
        const origin = "{{ $location }}";
        const destination = "{{ $destination }}";
        
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer();
        
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 7,
            center: { lat: -34.397, lng: 150.644 },
        });
        directionsRenderer.setMap(map);

        directionsService.route(
            {
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            },
            (response, status) => {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                } else {
                    window.alert("Directions request failed due to " + status);
                }
            }
        );
    }

    window.initMap = initMap;
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap"></script>
