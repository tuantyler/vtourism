var panorama, viewer, container, infospot, panel;

container = document.querySelector( '#container' );
panel = document.querySelector('#panel');

panorama = new PANOLENS.ImagePanorama( 'myhouse.jpg' );

infospot = new PANOLENS.Infospot( 350, PANOLENS.DataImage.Info );
infospot.position.set( 0, -2000, -5000 );
infospot.addHoverElement( panel, 150 );

panorama.add( infospot );

viewer = new PANOLENS.Viewer( { container: container } );
viewer.add( panorama );

var renderer, camera, scene, box;

renderer = new THREE.WebGLRenderer();
renderer.setClearColor(0xffffff);
renderer.setSize(panel.clientWidth, panel.clientHeight);
camera = new THREE.PerspectiveCamera(45, panel.clientWidth / panel.clientHeight, 1, 2000);
scene = new THREE.Scene();
console.log(infospot.element);
infospot.element.appendChild( renderer.domElement );

box = new THREE.Mesh(new THREE.BoxGeometry(10, 10, 10), new THREE.MeshNormalMaterial());
box.position.z = -20;
scene.add( box );

viewer.addUpdateCallback(function(){
  renderer.render(scene, camera);
  //box.rotation.y += 0.03;
});