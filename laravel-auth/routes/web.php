<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VTourUserController as user;

Auth::routes();
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']); 
});
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {
    Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
});
Route::get("/" , function() { return redirect("/home");});
Route::group(['middleware' => ['auth', 'activated', 'activity', 'twostep', 'checkblocked']], function () {
    Route::get("/home" , [user::class , "index"])->name("projects");
    Route::get("/get_vtours_as_json" , [user::class , "vToursJSON"])->name("getVToursJSON");
    Route::get("/vtours" , [user::class , "vTours"])->name("vtours");
    Route::post("/new_project" , [user::class , "newProject"])->name("newProject");
    Route::get("/delete_project/{id}" , [user::class , "deleteProject"])->name("deleteProject");
    Route::get("/edit_project/{id}" , [user::class , "editProject"])->name("editProject");
    Route::post("/update_project" , [user::class , "updateProject"])->name("updateProject");
    Route::post("/new_vtours" , [user::class , "newVTour"])->name("new_vtours");
    Route::get("/edit_vtour/{id}" , [user::class , "editVTour"])->name("edit_vtours");
    Route::get("/delete_vtour/{id}" , [user::class , "deleteVTour"])->name("delete_vtours");
    Route::post("/update_vtour" , [user::class , "updateVTour"])->name("update_vtour");
});    


Route::get('/update-database/{id}', [user::class, 'updateDatabase'])->name("update_database");
Route::get("/get_vtours_as_json_public" , [user::class , "vToursJSONPublic"])->name("vToursJSONPublic");
Route::get("/viewmap/{id}" , [user::class , "viewMap"])->name("viewMap");

Route::get("/tourxxml/{id}" , [user::class , "xml_generator"])->name("xml_generator");
Route::get("/view_vtour/{id}"  , [user::class , "viewVTour"])->name("view_vtours");

