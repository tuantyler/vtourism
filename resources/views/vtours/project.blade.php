
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
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-footer border-0">
                    <small id="deleteConfirmLabel">Bạn có chắc muốn gỡ vtour "<small class="fw-bold fst-italic" id="vtour_deletion_name"></small>" khỏi bản đồ? , điều này sẽ không xóa vtour trên hệ thống</small>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">dừng lại</button>
                    <button type="button" class="btn btn-sm btn-danger" id="vtour_deletion_confrimed"><i class="fa fa-trash"></i> đúng vậy</button>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar fixed-bottom">
        <div class="container-fluid">
            <div class="row w-100">
                <div class="col-md-4 d-flex align-items-center">
                    <span id="project_name"><a href="/home"><span class="muted">Project / </span></a>{{$project->projectName}}</span>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input class="form-control form-control-sm" id="_long_n_lat" placeholder="Nhập vĩ độ & tung độ">
                        <button class="btn btn-sm btn-success" onclick="var parts = document.getElementById('_long_n_lat').value.split(',');
                        map.getView().setCenter(ol.proj.fromLonLat([parseFloat(parts[1].trim()), parseFloat(parts[0].trim())]));"><i class="fa fa-map-marker"></i> Tới</button>
                    </div>
                </div>
                <div class="col-md-5">    
                    <button class="btn btn-sm btn-primary" id="getCurrentLocation"><i class="fa fa-location-arrow"></i> Lấy vị trí hiện tại</button>
                    <button class="btn btn-sm btn-info" id="btnSaveLocation"><i class="fa fa-arrow-right"></i> Lưu vị trí bản đồ</button>
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
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        let pinnedVTours = [];
        let vtour_delete_feature_obj;
        getVTours();

        let sidebarToggle = false;
        function toggleSidebar(evt){
            sidebarToggle ? evt.innerHTML = '<i class="fa fa-arrow-down"></i> Ẩn sidebar' : evt.innerHTML = '<i class="fa fa-arrow-up"></i> Hiện sidebar';
            sidebarToggle ? document.getElementsByClassName("sidebar")[0].style.display = "block" : document.getElementsByClassName("sidebar")[0].style.display = "none"
            sidebarToggle = !sidebarToggle;
        }
        async function getVTours(){
            let datas = await fetch('{{route("getVToursJSON")}}')
            vtours = await datas.json();
            await loadVTours();
        }

        var view = new ol.View({
            @if($project->coordinate !== null)
                center: ol.proj.fromLonLat({{$project->coordinate}}),
                zoom: {{$project->zoom}},
            @else 
                center: ol.proj.fromLonLat([parseFloat("108.25254426165128"), parseFloat("15.973379945361193")]),
                zoom: 18,
            @endif
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
        var mapElement = document.getElementById("map");
        mapElement.addEventListener("dragover", function (event) {
            event.preventDefault();
        });
        mapElement.addEventListener("drop", function (event) {
            event.preventDefault();
            let vtour = JSON.parse(event.dataTransfer.getData("text/plain")); 
            var coordinate = map.getEventCoordinate(event);
            vtour.coordinate = JSON.stringify(coordinate);
            document.getElementById(vtour.id).className = "btn btn-secondary btn-sm text-start w-100 mb-2";
            document.getElementById(vtour.id).setAttribute("draggable" , false);
            pinnedVTours.push(vtour);
            var marker = new ol.Feature({
                geometry: new ol.geom.Point(coordinate),
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

            fetch('{{route("update_vtour")}}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(vtour)
            });
            
        });
        map.on('click', function (evt) {
            var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
                return feature;
            });
            if (feature) {
                var url = "https://localhost/edit_vtour/" + feature.get('id');
                window.open(url, '_blank');
            }
        });
        map.on('pointerdown', function (evt) {
            if (event.button === 2) {
                var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
                    return feature;
                });
                if (feature) {
                    document.getElementById("vtour_deletion_name").innerHTML = feature.get("name");
                    vtour_delete_feature_obj = feature;
                    deleteModal.show();
                }
            }
        });
        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
        });
        document.getElementById("vtour_deletion_confrimed").addEventListener("click" , function(){
            pinnedVTours = pinnedVTours.filter((item) => {
                return item.id !== vtour_delete_feature_obj.get("id");
            });
            document.getElementById(vtour_delete_feature_obj.get("id")).className = "btn btn-light btn-sm text-start w-100 mb-2";
            document.getElementById(vtour_delete_feature_obj.get("id")).setAttribute("draggable" , true);
            vectorSource.removeFeature(vtour_delete_feature_obj);
            let updateObj = vtours.find(vtour => vtour.id === vtour_delete_feature_obj.get("id"));
            updateObj.coordinate = null;
            fetch('{{route("update_vtour")}}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updateObj)
            });
            deleteModal.hide();
        })
        document.getElementById("btnSaveLocation").addEventListener("click" , function(){
            console.log(ol.proj.toLonLat(view.getCenter()))
            var saveLocationAttr = {
                id: "{{$project->id}}",
                coordinate: JSON.stringify(ol.proj.toLonLat(view.getCenter())),
                zoom: view.getZoom()
            }
            fetch('{{route("updateProject")}}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(saveLocationAttr)
            });
            alert("Đã lưu!");
        })
        document.getElementById("getCurrentLocation").addEventListener("click" , function(){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        map.getView().setCenter(ol.proj.fromLonLat([parseFloat(lng), parseFloat(lat)]));
                    },
                    function (error) {
                        console.error("Error getting current position: ", error);
                    }
                );
            } else {
                console.error("Geolocation is not supported by this browser.");
            }                   
        })
        
    </script>
</body>

</html>