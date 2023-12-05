<!DOCTYPE html>
<html>
<head>
    <title>chỉnh sửa dự án</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta http-equiv="x-ua-compatible" content="IE=edge"/>
    <link rel="stylesheet" href="/js/krpano_editor/style/krpano.editor.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
        @-ms-viewport {
            width: device-width;
        }

        @media only screen and (min-device-width: 800px) {
            html {
                overflow: hidden;
            }
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            overflow: hidden;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #FFFFFF;
            background-color: #000000;
            font-family: 'Poppins', sans-serif !important;
        }
    </style>

    <script>
        function reload(){
            fetch('http://localhost/tourxxml/{{$vtour->id}}')
            .then(response => {
                if (response.ok) {
                location.reload(); // Reload the page on success
                } else {
                throw new Error('Error occurred.'); // Throw an error on non-2xx response
                }
            })
            .catch(error => {
                alert('Error: ' + error.message); // Display an alert with the error message
            });
        }
    </script>
</head>
<body>

<script src="/js/krpano_editor/tour.js"></script>

<div id="pano" style="width:100%;height:100%;">
    <noscript>
        <table style="width:100%;height:100%;">
            <tr style="vertical-align:middle;">
                <td>
                    <div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div>
                </td>
            </tr>
        </table>
    </noscript>
    <script>
        embedpano({
            swf: "/js/krpano_editor/tour.swf",
            xml: "{{$vtour->url}}/tour.xml",
            target: "pano",
            html5: "auto",
            mobilescale: 1.0,
            passQueryParameters: true
        });
    </script>
</div>

<div class="frame" name="#viewFrame">
    <button onclick="setAsDefaultView()" class="btn btn-blue">đặt làm góc chính</button>
    <div class="frame-line frame-line-top"></div>
    <div class="frame-line frame-line-right"></div>
    <div class="frame-line frame-line-bottom"></div>
    <div class="frame-line frame-line-left"></div>
    <span class="angle angle-tl"><i class="angle-a"></i><i class="angle-b"></i></span>
    <span class="angle angle-tr"><i class="angle-a"></i><i class="angle-b"></i></span>
    <span class="angle angle-bl"><i class="angle-a"></i><i class="angle-b"></i></span>
    <span class="angle angle-br"><i class="angle-a"></i><i class="angle-b"></i></span>
</div>
<div class="overlay overlay-right">
    <div class="save-wrap">
        <button onclick="save()" id="isEdited" class="btn btn-blue">saved</button>
    </div>
    <div class="tool-panel">
        <ul class="tool-btn-lst">
            <li>
                <span class="btn J-tool-btn btn-blue" data-target="#viewFrame">góc nhìn</span>
                <div class="tool-main" style="display: block" name="#viewFrame">
                    <section class="tool-section">
                        <div style="margin: 5px;">
                            <label style="padding-right: 5px;vertical-align: middle;" for="autoSpin">tự động xoay</label>
                            <input type="checkbox" name="autoSpin" id="autoSpin" style="vertical-align: middle;"
                                   onclick="autoSpinClick()"/>
                        </div>
                        <div id="waitTimeInput" style="display: none;margin: 5px;">
                            <label style="padding-right: 5px;vertical-align: middle;" for="waitTime">tốc độ</label>
                            <input type="text" class="wait-time-input" id="waitTime" onblur="autoSpinClick()"/>
                            <label style="padding-left: 5px">s</label>
                        </div>
                        <div style="margin: 5px;">
                            <label>(áp dụng cho tất cả các kịch bản theo mặc định)</label>
                        </div>
                    </section>
                    <section class="tool-section">
                        <div style="margin: 5px;">
                            <label>fov ban đầu
                                <input type="text" class="fov-input" id="initFov" onblur="updateFov()"/>
                            </label>
                        </div>
                        <section id="sample-pb">
                            <article>
                                <div class="number-pb">
                                    <div class="number-pb-shown" style="display: none">
                                        <div class="triangle triangle-up-left" style="float: left;"></div>
                                        <div class="triangle triangle-down"></div>
                                        <div class="triangle triangle-up-right" style="float: right;"></div>
                                    </div>
                                    <div class="number-pb-num" style="left: 70%;">
                                    </div>
                                </div>
                            </article>
                        </section>
                        <div style="margin: 5px;">
                            <label>fov tối thiểu    
                                <input type="text" class="fov-input" id="initFovMin" onblur="updateFov()"/>
                            </label>
                        </div>
                        <div style="margin: 5px;">
                            <label>fov tối đa 
                                <input type="text" class="fov-input" id="initFovMax" onblur="updateFov()"/>
                            </label>
                        </div>
                        <div style="margin: 5px;">
                            <input type="button" style="vertical-align: middle;" value="áp dụng cho tất cả các kịch bản" class="btn btn-blue"
                                   onclick="fovForAll()"/>
                        </div>
                    </section>
                </div>
            </li>
            <li>
                <span class="btn J-tool-btn" data-target="#toolHot">Hotspot</span>
                <div class="tool-main" name="#toolHot" id="hotToolButton">
                    <section class="tool-section">
                        <h3 style="margin-bottom: 5px;">Loại hotspot</h3>
                    </section>
                    <section class="tool-section">
                        <h3 style="margin-bottom: 5px;">Danh sách hotspot</h3>
                        <ul>
                            <div id="hotSpotList"></div>
                            <script id="tplHotSpotList" type="text/html">
                                @verbatim
                                {{each list as eachSpot}}
                                <div class="hot-spot-list" name="{{eachSpot.name}}Hover">
                                    <div class="hot-spot-img" style="background-image: url('{{eachSpot.url}}')"
                                         spot-style="{{eachSpot.style}}"></div>
                                    <div class="hot-spot-text">{{eachSpot.linkedscene}}</div>
                                    <div class="hot-spot-del" onclick="removeHotSpot('{{eachSpot.name}}')">-</div>
                                </div>
                                {{/each}}
                                @endverbatim
                            </script>
                        </ul>
                        <button class="hot-spot-button" onclick="showAddHotSpot()">+</button>
                    </section>
                </div>
            </li>
            <li>
                <span class="btn J-tool-btn" onclick="reload()">Reload</span>
            </li>


        </ul>
    </div>
</div>
<div class="left-column">
    <div class="scene-select">
        <div class="blue-banner"></div>
        <div class="select_scene_text">
            <p class="p_title">lựa chọn vtours</p>
        </div>
        <div class="blue-banner"></div>
        <ul style="margin: 0;padding: 0;">
            <div id="sceneList"></div>
            <script id="tplSceneList" type="text/html">
                @verbatim
                {{each list as scene}}
                <li key="{{scene.index}}" name="{{scene.name}}" class="li-scene">
                    <div class="thumbs-img-scene-list"
                         style='background-image: url("panos/{{scene.index+1}}.tiles/thumb.jpg")'></div>
                    <div class="li-scene-span" onclick="changeScene({{scene.index}})">{{scene.name}}</div>
                    <input type="hidden" value="{{scene.name}}" class="li-scene-input" onblur="doRename($(this))"/>
                    <input type="button" class="scene-li-button" onclick="rename($(this).prev())"/>
                    <div class="circle" onclick="selectWelcomeScene({{scene.index}})"></div>
                </li>
                {{/each}}
                @endverbatim
            </script>
        </ul>
    </div>
</div>
<div class="add-hot-pot" style="display: none" name="#toolHot">
    <div class="add-hot-banner">Thêm hotspot<a class="div-close" onclick="hideAddHotSpot()">×</a></div>
    <div class="progress-banner">
        <div class="progress-title progress-title-on" id="selectStyleTitle"><span style="line-height: 30px">Chọn icon</span>
        </div>
        <div class="progress-title" id="goToSceneTitle"><span style="line-height: 30px">Chọn VTour</span></div>
        <div class="progress-title" id="writeTitleTitle"><span style="line-height: 30px">Hoàn thành</span></div>
    </div>

    <div id="selectStyle" class="add-hot-content">
        <div class="add-hot-bottom-div">
            <div class="hot-style"
                 style="background-image: url('/js/krpano_editor/skin/vtourskin_hotspot.png')"
                 name="skin_hotspotstyle"></div>
        </div>
        <div class="add-hot-button" onclick="nextToSelectTargetScene()">Tiếp tục</div>
    </div>
    <div id="goToScene" class="add-hot-content" style="display: none">
        <div class="add-hot-bottom-div">
            <div id="targetScene"></div>
            <script id="tplTargetScene" type="text/html">
                @verbatim
                {{each list as each}}
                <div class="select-scene-div" name="{{each.name}}">
                    {{each.name}}
                    <div class="thumbs-img" style='background-image: url("panos/{{each.index}}.tiles/thumb.jpg")'></div>
                </div>
                {{/each}}
                @endverbatim
            </script>
        </div>
        <div class="add-hot-button" onclick="nextToWriteTitle()">Tiếp tục</div>
    </div>
    <div id="writeTitle" class="add-hot-content write-title" style="display: none">
        <div class="add-hot-bottom-div">
            <p style="padding: 50px;"><label for="addHotTitle">Đặt tên cho hotspot</label></p>
            <input type="text" id="addHotTitle"/>
        </div>
        <div class="add-hot-button" onclick="addHotSpot()">Thêm hotspot</div>
    </div>
</div>
<script src="/js/krpano_editor/script/jQuery-2.1.4.min.js"></script>

<script>
 var filepathGlobal = "{{$vtour->url . "/tour.xml"}}"
</script>
<script src="/js/krpano_editor/script/krpano.editor.js"></script>
<script src="/js/krpano_editor/script/template.min.js"></script>
</body>
</html>
