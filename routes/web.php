<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

// get requests

Route::get('/', 'CalendarController@redirect');

Route::get('/calendar', 'CalendarController@index')->name('welcome')->middleware('verified');

Route::get('/profile', 'ProfileController@index')->name('profile')->middleware('verified');

Route::get('/controlpanel', 'ControlPanelController@index')->name('controlpanel')->middleware('verified');

Route::get('/controlpanel/{group_id}', 'GroupPanelController@index')->middleware('verified');

//---------------------

// profile request

Route::put('/profile/changePassword', 'ProfileController@changePassword')->middleware('verified');

Route::put('/profile/changeToken', 'ProfileController@changeToken')->middleware('verified');

Route::put('/profile/changeUserData', 'ProfileController@changeUserData')->middleware('verified');

Route::post('/profile/uploadAvatar', 'ProfileController@uploadAvatar')->middleware('verified');

//---------------------

// Select ajax requests

Route::get('/calendar/viewSelectPostData', 'SelectPostController@viewPostData')->middleware('verified');

//---------------------

// Create, update, view and delete Post 

Route::post('/calendar/createEvent', 'CalendarPostController@createEvent')->middleware('verified');

Route::post('/calendar/getEvents', 'CalendarPostController@getEvents')->middleware('verified');

Route::put('/calendar/updateEvent', 'CalendarPostController@updateEvent')->middleware('verified');

Route::put('/calendar/updatePostData', 'CalendarPostController@updatePostData')->middleware('verified');

Route::get('/calendar/viewPostData', 'CalendarPostController@viewPostData')->middleware('verified');

Route::delete('/calendar/deleteEvent', 'CalendarPostController@deleteEvent')->middleware('verified');

Route::delete('/calendar/deletePostAttachment', 'CalendarPostController@deletePostAttachment')->middleware('verified');

//---------------------

// Control Panel groups hiding 

Route::put('/controlpanel/hideGroup', 'ControlPanelController@hideGroup')->middleware('verified');

//---------------------

// Parse Panel Start, Stop and Delete sources

Route::put('/parsepanel/startSourceParse', 'GroupPanelController@startSourceParse')->middleware('verified');

Route::put('/parsepanel/stopSourceParse', 'GroupPanelController@stopSourceParse')->middleware('verified');

Route::delete('/parsepanel/deleteSource', 'GroupPanelController@deleteSource')->middleware('verified');

//---------------------

//VK Requests 

Route::post('/vkRequest/uploadImage', 'VkRequestController@uploadImage')->middleware('verified');

Route::get('/vkRequest/post', 'VkRequestController@post');

Route::post('/vkRequest/getUserGroups', 'VkRequestController@getUserGroups')->middleware('verified');

Route::post('/vkRequest/parsePosts', 'VkRequestController@parsePosts')->middleware('verified');

Route::post('/vkRequest/getSourceGroupAndPostsQuantity', 'VkRequestController@getSourceGroupAndPostsQuantity')->middleware('verified');

Route::get('/vkRequest/sourceParse', 'VkRequestController@sourceParse');

//---------------------
