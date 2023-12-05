<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v6.5.0/css/ol.css" type="text/css">
    <script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v6.5.0/build/ol.js"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
    body {
        font-family: 'Inter', sans-serif;
        background: #1e1e1e;
        color: white;
    }
    .navbar {
        padding: 10px;
        background-color: rgba(23, 23, 23, 0.4) !important;
        border-top: 1px solid #444444;
        backdrop-filter: blur(4px);
    }
    #project_name { 
        font-size: 14px;
    }

    #project_name .muted {
        font-weight: 600;
        color: #FFFFFFB3;
    }
    #map {
        filter: invert(1) hue-rotate(180deg) grayscale(0.5);
    }
    .sidebar {
        border-left: 1px solid #444444;
        right: 0;
        position: absolute;
        z-index: 3;
        height: 100vh;
        background-color: rgba(23, 23, 23, 0.4);
        padding: 10px;
        width: 320px;
        backdrop-filter: blur(4px);
        overflow-y: auto;
    }
    .form-control {
        background: #373737 !important;
        color: white !important;
        border: none;
    }
    .form-control:focus {
        box-shadow: none;
    }
    .form-control::-webkit-input-placeholder {
        color: rgba(245, 245, 245, 0.325);
    }
    .modal-content {
        background: #2c2c2c;
    }
    .btn-secondary {
        background: #373737 !important;
    }
</style>

<body>
    <div class="navbar fixed-bottom">
        <div class="container-fluid">
            <div class="row w-100">
                <div class="col-md-4 d-flex align-items-center">
                    <span id="project_name"><a href="/home"><span class="muted">Project / </span></a>Tổng quan</span>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input class="form-control form-control-sm" id="_long_n_lat" placeholder="Nhập vĩ độ & tung độ">
                        <button class="btn btn-sm btn-success" onclick="var parts = document.getElementById('_long_n_lat').value.split(',');
                        map.getView().setCenter(ol.proj.fromLonLat([parseFloat(parts[1].trim()), parseFloat(parts[0].trim())]));"><i class="fa fa-map-marker"></i> Tới</button>
                    </div>
                </div>
                <div class="col-md-5">    
                    <button class="btn btn-sm btn-primary"><i class="fa fa-location-arrow"></i> Lấy vị trí hiện tại</button>
                    <button class="btn btn-sm btn-dark" style="float: right;" onclick="toggleSidebar(this)"><i class="fa fa-arrow-up"></i> Ẩn sidebar</button>
                </div>
            
            </div> 
        </div>
    </div>
    <div class="sidebar">
        <small class="fw-bold">VTours</small>
        <div class="mt-2" id="pinpoints"></div>
    </div>

    <div id="map" style="height: 100vh;"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const pinpoints = document.getElementById("pinpoints");
        let vtours = [];
       
        let pinnedVTours = [];

        getVTours();

        let sidebarToggle = false;
        function toggleSidebar(evt){
            sidebarToggle ? evt.innerHTML = '<i class="fa fa-arrow-down"></i> Ẩn sidebar' : evt.innerHTML = '<i class="fa fa-arrow-up"></i> Hiện sidebar';
            sidebarToggle ? document.getElementsByClassName("sidebar")[0].style.display = "block" : document.getElementsByClassName("sidebar")[0].style.display = "none"
            sidebarToggle = !sidebarToggle;
        }
        async function getVTours(){
            let datas = await fetch('{{route("vToursJSONPublic")}}')
            vtours = await datas.json();
            loadVTours();
        }

        var view = new ol.View({
            center: ol.proj.fromLonLat([parseFloat("108.25254426165128"), parseFloat("15.973379945361193")]),
            zoom: 18,
            maxZoom: 22
        });
        var map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: view
        });
        function loadVTours(){
            for (const vtour of vtours) {
                console.log(vtour);
                let button = document.createElement("button");
                button.id = vtour.id;
         
                if (vtour.coordinate !== null) {
                    button.setAttribute("draggable", false); 
                    button.className = 'btn btn-secondary btn-sm text-start w-100 mb-2';
                    pinnedVTours.push(vtour);
                    var marker = new ol.Feature({
                        geometry: new ol.geom.Point(JSON.parse(vtour.coordinate)),
                    });
                    marker.setStyle(
                        new ol.style.Style({
                            image: new ol.style.Icon({
                                src: 'https://openlayers.org/en/latest/examples/data/icon.png',
                            }),
                        })
                    );
                    marker.setProperties(vtour);
                    vectorLayer.getSource().addFeature(marker);
                }
                else {
                    button.setAttribute("draggable", true); 
                    button.className = 'btn btn-light btn-sm text-start w-100 mb-2';
                }
       
                button.innerHTML = '<i class="fa fa-thumb-tack me-3"></i>' + vtour.name;
                  
                button.addEventListener("dragstart", function() {
                    event.dataTransfer.setData("text/plain", JSON.stringify(vtour));
                });
                button.addEventListener("click", function() {
                    const targetVTour = pinnedVTours.find(obj => obj.id === vtour.id);
                    if (targetVTour != null) {
                        map.getView().setCenter(JSON.parse(targetVTour.coordinate));
                    }
                });
                pinpoints.appendChild(button);
            }
        }
        var vectorSource = new ol.source.Vector();
        var vectorLayer = new ol.layer.Vector({
            source: vectorSource
        });
        map.addLayer(vectorLayer);  
        map.on('click', function (evt) {
            var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
                return feature;
            });
            if (feature) {
                var url = "https://localhost/view_vtour/" + feature.get('id');
                window.open(url, '_blank');
            }
        }); 
    </script>
</body>

</html>