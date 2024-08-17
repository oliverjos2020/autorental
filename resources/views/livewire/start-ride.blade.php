<div>
    <div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Driver Location</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div id="location-error" style="color: red; display: none;">
            Location is required to use this service. Please allow location access.
            <br>
            <a href="#" onclick="showInstructions()">How to enable location access</a>
        </div>

        <div id="location-instructions" style="display: none;">
            <p>To enable location access, please follow these steps:</p>
            <ul>
                <li>Open your browser's settings.</li>
                <li>Navigate to the privacy or site settings section.</li>
                <li>Find this website in the list and allow location access.</li>
            </ul>
        </div>

        <form id="location-form" method="POST" action="">
            @csrf
            <input type="text" name="latitude" id="latitude" wire:model="latitude">
            <input type="text" name="longitude" id="longitude" wire:model="longitude">
            <button type="button" onclick="getLocation()">Update Location</button>
        </form>
    </div>
    <div class="col-md-8">
        <div id="map" style="height: 100vh; width: 100%;"></div>
    </div>
</div>

<script>
    function initMap(lat, lng) {
        const mapOptions = {
            zoom: 15,
            center: { lat: lat, lng: lng },
        };

        const map = new google.maps.Map(document.getElementById('map'), mapOptions);

        new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
        });

        const circle = new google.maps.Circle({
            map: map,
            radius: 500, // 500 meters radius
            center: { lat: lat, lng: lng },
            fillColor: '#70c2ff',
            fillOpacity: 0.35,
            strokeColor: '#70c2ff',
            strokeOpacity: 0.8,
            strokeWeight: 2,
        });

        google.maps.event.addListener(map, 'zoom_changed', function () {
            const zoom = map.getZoom();
            circle.setRadius(500 * Math.pow(2, 15 - zoom));
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        console.log('Latitude:', lat, 'Longitude:', lng);

        initMap(lat, lng);
    }

    function showError(error) {
        document.getElementById('location-error').style.display = 'block';
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }

    function showInstructions() {
        document.getElementById('location-instructions').style.display = 'block';
    }

    window.addEventListener('load', getLocation);
</script>
{{-- <script>
let map, infoWindow;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 6,
  });
  infoWindow = new google.maps.InfoWindow();

  const locationButton = document.createElement("button");

  locationButton.textContent = "Pan to Current Location";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
  locationButton.addEventListener("click", () => {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          infoWindow.setPosition(pos);
          infoWindow.setContent("Location found.");
          infoWindow.open(map);
          map.setCenter(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        },
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
  });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation.",
  );
  infoWindow.open(map);
}

window.initMap = initMap;
</script> --}}

</div>