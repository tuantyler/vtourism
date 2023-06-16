<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Http;
use File;

class VTourUserController extends Controller
{
    public function deleteVTour($editID){
        DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("id", $editID)->delete();
        return redirect()->back();
    }

    public function newProject(Request $res){
        $submit_arr = $res->except("_token");
        $submit_arr['ownerEmail'] = Auth::user()->email;
        DB::table("projects")->insert($submit_arr);
        return redirect()->back();
    }

    public function index(){
        $user = Auth::user();
        $projects = DB::table("projects")->where("ownerEmail" , Auth::user()->email)->get();
        return view('vtours.index' , compact("projects"));
    }

    public function deleteProject($deleteID) {
        $projects = DB::table("projects")->where("ownerEmail" , Auth::user()->email)->where("id", $deleteID)->delete();
        return redirect()->back();
    }

    public function editProject($editID) {
        $project = DB::table("projects")->where("ownerEmail" , Auth::user()->email)->where("id", $editID)->first();

        if ($project == null) {
            return "Project không hợp lệ hoặc bạn không có quyền chỉnh sửa project này";
        }
        DB::table("projects")->where("ownerEmail" , Auth::user()->email)->where("id", $editID)->update(["lastEdit" => date('Y-m-d H:i:s')]);
        return view("vtours.project" , compact("project"));
    }

    public function vTours(){
        $vtours = DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->get();
        return view("vtours.vtours" , compact("vtours"));
    }

    public function createVTour(){
        // $vtours = DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("processed" , 1)->get();
        // return view("vtours.vtours" , compact("vtours"));
    }

    public function newVTour(Request $request){
        $request->validate([
            'videoPath' => 'required|mimes:mp4,mov,avi,wmv' 
        ]);
        $file = $request->file('videoPath');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('videos'), $filename);
        $insertarr = $request->except("_token" , "videoPath");
        $insertarr['videoPath'] = 'videos/' . $filename;
        $insertarr['ownerEmail'] = Auth::user()->email;
        $insertarr['processed'] = 0;
        $id = DB::table("vtours")->insertGetId($insertarr);
        
        try {
            $response = Http::timeout(1)->post('http://localhost:5000/process-video', [
                'id' => $id,
                'videofile' => $filename,
            ]);
        } catch(\Exception $e) {
            // Do nothing 
        }
        
        return redirect()->back();   
    }

    public function editVTour($editID){
        $vtour = DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("id", $editID)->first();
        DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("id", $editID)->update(["lastEdit" => date('Y-m-d H:i:s')]);
        return view("vtours.editVTour" , compact("vtour"));
    }

    public function viewMap($id){
        $project = DB::table("projects")->where("id", $id)->first();
        if ($project == null) {
            return view("vtours.viewMapNone");
        }
        return view("vtours.viewMap" , compact("project"));
    }
    public function vToursJSON(){
        $vtours = DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("processed",1)->get();
        echo json_encode($vtours , JSON_UNESCAPED_UNICODE);
    }

    public function vToursJSONPublic(){
        $vtours = DB::table("vtours")->where("processed",1)->where("coordinate" , "!=" , null)->get();
        echo json_encode($vtours , JSON_UNESCAPED_UNICODE);
    }   

    function updateVTour(Request $res){
        $vtour = $res->all();
        DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("id",$vtour["id"])->update($vtour);
    }

    function updateProject(Request $res){
        $project = $res->all();
        DB::table("projects")->where("ownerEmail" , Auth::user()->email)->where("id",$project["id"])->update($project);
    }

    public function updateDatabase($id)
    {
        $video = DB::table('vtours')->where('id', $id)->first();

        if (!$video) {
            return response()->json([
                'message' => 'Video not found in the database.',
            ], 404);
        }

        $videoPath = $video->videoPath;
        $videoName = pathinfo($videoPath, PATHINFO_FILENAME);
        $videoID = substr($videoName, strpos($videoName, '/'));

        $processedFolder = '/processed/' . $videoID;
        $vtourPath = $processedFolder . '/vtour';
        
        if (is_dir(public_path($vtourPath))) {
            DB::table('vtours')
                ->where('id', $id)
                ->update(['url' => $vtourPath . "" , 'processed' => 1]);

            return response()->json([
                'message' => 'URL updated in the database.',
            ]);
        }

        

        return response()->json([
            'message' => 'pano does not exist in the processed folder.',
        ], 404);
    }

    public function xml_generator($id){
        $vtour = DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("id",$id)->first();
        $vtours = DB::table("vtours")->where("ownerEmail" , Auth::user()->email)->where("processed",1)->get();
        $xmlContent = view('vtours.xml', ['vtours' => $vtours])->render();
        File::put(substr($vtour->url, 1) . "/tour.xml", $xmlContent);
    }

    public function viewVTour($id){
        $vtour = DB::table("vtours")->where("id", $id)->first();
        return view("vtours.viewVTour" , compact("vtour"));
    }

}
