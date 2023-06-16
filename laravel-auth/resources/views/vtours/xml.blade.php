<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<krpano version="1.19" title="Virtual Tour">
	<include url="/js/krpano_editor/skin/vtourskin.xml"/>
	<skin_settings maps="false" maps_type="google" maps_bing_api_key="" maps_google_api_key="" maps_zoombuttons="false" gyro="true" webvr="true" webvr_gyro_keeplookingdirection="false" webvr_prev_next_hotspots="true" littleplanetintro="false" title="true" thumbs="true" thumbs_width="120" thumbs_height="80" thumbs_padding="10" thumbs_crop="0|40|240|160" thumbs_opened="false" thumbs_text="false" thumbs_dragging="true" thumbs_onhoverscrolling="false" thumbs_scrollbuttons="false" thumbs_scrollindicator="false" thumbs_loop="false" tooltips_buttons="false" tooltips_thumbs="false" tooltips_hotspots="false" tooltips_mapspots="false" deeplinking="false" loadscene_flags="MERGE" loadscene_blend="OPENBLEND(0.5, 0.0, 0.75, 0.05, linear)" loadscene_blend_prev="SLIDEBLEND(0.5, 180, 0.75, linear)" loadscene_blend_next="SLIDEBLEND(0.5,   0, 0.75, linear)" loadingtext="loading..." layout_width="100%" layout_maxwidth="814" controlbar_width="-24" controlbar_height="40" controlbar_offset="20" controlbar_offset_closed="-40" controlbar_overlap.no-fractionalscaling="10" controlbar_overlap.fractionalscaling="0" design_skin_images="vtourskin.png" design_bgcolor="0x2D3E50" design_bgalpha="0.8" design_bgborder="0" design_bgroundedge="1" design_bgshadow="0 4 10 0x000000 0.3" design_thumbborder_bgborder="3 0xFFFFFF 1.0" design_thumbborder_padding="2" design_thumbborder_bgroundedge="0" design_text_css="color:#FFFFFF; font-family:Arial;" design_text_shadow="1"/>
	<action name="startup" autorun="onstart">if(startscene === null OR !scene[get(startscene)], copy(startscene,scene[0].name); );loadscene(get(startscene), null, MERGE);if(startactions !== null, startactions() );js('onready');</action>
	<scene name="Main" title="1" onstart="" havevrimage="true" thumburl="/js/krpano_editor/panos/1.tiles/thumb.jpg" lat="" lng="" heading="">
		<view hlookat="135.54120522819224" vlookat="3.2364065579371784" fovtype="MFOV" fov="120" fovmin="120" fovmax="120" limitview="auto"/>
		<preview url="/js/krpano_editor/panos/1.tiles/preview.jpg"/>
		<image type="CUBE" multires="true" tilesize="512" if="!webvr.isenabled">
			<level tiledimagewidth="2560" tiledimageheight="2560">
				<cube url="/js/krpano_editor/panos/1.tiles/%s/l3/%v/l3_%s_%v_%h.jpg"/>
			</level>
			<level tiledimagewidth="1280" tiledimageheight="1280">
				<cube url="/js/krpano_editor/panos/1.tiles/%s/l2/%v/l2_%s_%v_%h.jpg"/>
			</level>
			<level tiledimagewidth="640" tiledimageheight="640">
				<cube url="/js/krpano_editor/panos/1.tiles/%s/l1/%v/l1_%s_%v_%h.jpg"/>
			</level>
		</image>
		<image if="webvr.isenabled">
			<cube url="/js/krpano_editor/panos/1.tiles/vr/pano_%s.jpg"/>
		</image>
		<autorotate enabled="false" waittime="1.5" accel="1.0" speed="5.0" horizon="0.0"/>
		<hotspot ath="163.95746353047562" atv="-10.285716331067048" linkedscene="test2" name="spot1686608796167" style="skin_hotspotstyle" dive="true"/>
	</scene>
	@foreach($vtours as $vtour) 

	<scene name="{{$vtour->name}}" title="pano" onstart="" thumburl="{{$vtour->url}}/panos/pano.tiles/thumb.jpg" lat="" lng="" alt="" heading="">
		<control bouncinglimits="calc:image.cube ? true : false" />
		<view hlookat="0.0" vlookat="0.0" fovtype="MFOV" fov="120" maxpixelzoom="2.0" fovmin="70" fovmax="140" limitview="auto" />
		<preview url="{{$vtour->url}}/panos/pano.tiles/preview.jpg" />
		<image hfov="1.00" vfov="0.078889" voffset="0.00">
			<flat url="{{$vtour->url}}/panos/pano.tiles/l%l/%0v/l%l_%0v_%0h.jpg" multires="512,10752x848" />
		</image>
	</scene>

	@endforeach
</krpano>