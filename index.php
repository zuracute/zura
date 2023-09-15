<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Crime Map with Layers Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Include Leaflet Layers Control plugin -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-layers@3.3.1/dist/leaflet.control-layers.css" />
    <script src="https://unpkg.com/leaflet-control-layers@3.3.1/dist/leaflet.control-layers.js"></script>
    <!-- Include Leaflet Routing Machine CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <div id="map" style="width: 100%; height: 600px;"></div>
    <button id="locateButton">Find My Location</button>

    <!-- Add this code after the map div -->
    <div id="routing" style="display: none;">
        <h2>Route to:</h2>
        <select id="destinationSelect">
            <option value="">Select a destination</option>
            <option value="Sta. Catalina">Sta. Catalina</option>
            <option value="Talon Talon">Talon Talon</option>
            <!-- Add options for other destinations -->
        </select>
        <button id="calculateRouteButton">Calculate Route</button>
        <div id="routeInstructions"></div>
    </div>

    <script>
        var map = L.map('map').setView([6.917, 122.067], 12);
        var userMarker; // Declare a variable to store the user's location marker

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var barangayMarkers = L.layerGroup();
        var policeMarkers = L.layerGroup();
        var fireMarkers = L.layerGroup();
        var ERTMarkers = L.layerGroup();

        // Create a layers control object
        var layersControl = L.control.layers(null, null, {
            collapsed: false // Keep the layers control expanded by default
        }).addTo(map);

        // Add base layers to the control
        var baseLayers = {
            "Street Map": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }),
            "Satellite Map": L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 19,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            })
        };

        layersControl.addBaseLayer(baseLayers["Street Map"], "Street Map");
        layersControl.addBaseLayer(baseLayers["Satellite Map"], "Satellite Map");

        // Add overlay layers to the control
        var overlayLayers = {
            "Barangays": barangayMarkers,
            "Police Stations": policeMarkers,
            "Fire Stations": fireMarkers,
            "ERT Stations": ERTMarkers
        };

        layersControl.addOverlay(overlayLayers["Barangays"], "Barangays");
        layersControl.addOverlay(overlayLayers["Police Stations"], "Police Stations");
        layersControl.addOverlay(overlayLayers["Fire Stations"], "Fire Stations");
        layersControl.addOverlay(overlayLayers["ERT Stations"], "ERT Stations");

        // Function to create and return a marker with pop-up content
        function createMarkerWithPopup(lat, lon, title, description, imageUrl) {
            var marker = L.marker([lat, lon]);

            var popupContent = `
                <h3>${title}</h3>
                <img src="${imageUrl}" alt="${title}" style="max-width: 100px; height: auto;">
                <p>${description}</p>
                <p>Emergency Call: <strong>9999</strong></p>
            `;

            marker.bindPopup(popupContent);

            // Open the popup on marker hover
            marker.on('mouseover', function () {
                this.openPopup();
            });

            return marker;
        }

        // Add markers with pop-ups
        createMarkerWithPopup(
            6.908701888392145, 122.08788476609179,
            "Sta. Catalina",
            "This is Sta. Catalina.",
            "https://via.placeholder.com/100"
        ).addTo(map);

        createMarkerWithPopup(
            6.910778271212043, 122.1112297049799,
            "Talon Talon",
            "This is Talon Talon.",
            "https://via.placeholder.com/100"
        ).addTo(map);

        createMarkerWithPopup(
            6.915836030577077, 122.146410310371,
            "Mampang",
            "This is Mampang.",
            "https://via.placeholder.com/100"
        ).addTo(map);

        createMarkerWithPopup(
            6.9091288,122.0868708,55,
            "Sta. Catalina Barangay Hall",
            "This is Barangay Hall, Sta. Catalina.",
            "https://via.placeholder.com/100"
        ).addTo(barangayMarkers);

        createMarkerWithPopup(
            6.909797365926736, 122.11237256900135,
            "Talon-Talon Barangay Hall",
            "This is Barangay Hall, Talon-Talon.",
            "https://via.placeholder.com/100"
        ).addTo(barangayMarkers);

        createMarkerWithPopup(
            6.915050254770022, 122.13571787680372,
            "Mampang Barangay Hall ",
            "This is Barangay Hall, Mampang.",
            "https://via.placeholder.com/100"
        ).addTo(barangayMarkers);

        createMarkerWithPopup(
            6.9191687,122.1038429,89,
            "Tugbungan Police Station",
            "This is Police Station.",
            "https://via.placeholder.com/100"
        ).addTo(policeMarkers);

        createMarkerWithPopup(
            6.9175928,122.0900578,179,
            "tetuan Police Station",
            "This is Police Station.",
            "https://via.placeholder.com/100"
        ).addTo(policeMarkers);

        createMarkerWithPopup(
            6.9091301,122.0848843,357,
            "Sta. Catalina Police Center",
            "This is Sta. Catlina Police Station.",
            "https://via.placeholder.com/100"
        ).addTo(policeMarkers);

        createMarkerWithPopup(
            6.918649181459553, 122.1489652831065,
            "Barangay Mampang Fire Station",
            "Mampang Fire Station.",
            "https://via.placeholder.com/100"
        ).addTo(fireMarkers);

        createMarkerWithPopup(
            6.9091197,122.0868882,55,
            "Sta. Catalina Sub Fire Station",
            "This is Sub Fire Station, Sta. Catalina.",
            "https://via.placeholder.com/100"
        ).addTo(fireMarkers);

        createMarkerWithPopup(
            6.9324, 122.0729,
            "ERT 1",
            "This is ert Station 2.",
            "https://via.placeholder.com/100"
        ).addTo(ERTMarkers);

        createMarkerWithPopup(
            6.9324, 122.0661,
            "ERT 2",
            "This is ert Station 2.",
            "https://via.placeholder.com/100"
        ).addTo(ERTMarkers);

        

        document.getElementById('locateButton').addEventListener('click', function () {
    // Remove the previous user location marker if it exists
    if (userMarker) {
        map.removeLayer(userMarker);
    }

    // Attempt to get the user's more accurate current location using the device's GPS
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;

            userMarker = L.marker([userLat, userLng]).addTo(map);
            userMarker.bindPopup("Your Location").openPopup();
        }, function (error) {   
            if (error.code === error.PERMISSION_DENIED) {
                // User denied geolocation, show a friendly message asking them to enable it
                Swal.fire({
                    icon: 'info',
                    title: 'Location Services Disabled',
                    text: 'To use this feature, please enable location services for your browser. This will allow us to find your current location.',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            } else {
                // Handle other geolocation errors as before
                switch (error.code) {
                    case error.POSITION_UNAVAILABLE:
                        Swal.fire({
                            icon: 'error',
                            title: 'Location Unavailable',
                            text: 'Location information is unavailable. Please ensure your device\'s GPS is enabled and try again.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        break;
                    case error.TIMEOUT:
                        Swal.fire({
                            icon: 'error',
                            title: 'Request Timeout',
                            text: 'The request to get user location timed out. Please try again later.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        break;
                    case error.UNKNOWN_ERROR:
                        Swal.fire({
                            icon: 'error',
                            title: 'Unknown Error',
                            text: 'An unknown error occurred. Please try again.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        break;
                }
            }
        }, { timeout: 30000 });
    } else {
        // Geolocation not supported, show a message
        Swal.fire({
            icon: 'error',
            title: 'Geolocation Not Supported',
            text: 'Geolocation is not supported by your browser. Please use a different browser or enable location services.',
            customClass: {
                confirmButton: 'btn btn-danger'
            }
        });
    }
});

        // Define routing control
        var routingControl = L.Routing.control({
            waypoints: [], // This will be populated with user's current location and destination
            routeWhileDragging: true,
            geocoder: L.Control.Geocoder.nominatim(),
            show: false,
            routeDragTimeout: 250
        }).addTo(map);

        // Function to calculate and display the route
        function calculateRoute(destination) {
            var userLatLng = userMarker.getLatLng();
            var destinationLatLng;

            // Determine the destination coordinates based on the selected destination
            switch (destination) {
                case "Sta. Catalina":
                    destinationLatLng = L.latLng(6.9393, 122.0989);
                    break;
                case "Talon Talon":
                    destinationLatLng = L.latLng(6.9397, 122.0787);
                    break;
                // Add cases for other destinations
            }

            // Set waypoints for routing
            routingControl.setWaypoints([userLatLng, destinationLatLng]);

            // Display the routing control
            routingControl.show();

            // Calculate and display the route
            routingControl.route();
        }

        // Event listener for Calculate Route button
        document.getElementById('calculateRouteButton').addEventListener('click', function () {
            var destinationSelect = document.getElementById('destinationSelect');
            var selectedDestination = destinationSelect.value;

            if (selectedDestination) {
                calculateRoute(selectedDestination);
            } else {
                alert("Please select a destination.");
            }
        });
    </script>
</body>
</html>
