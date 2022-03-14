<?php
Route::group([
  'prefix' => '/api/file-uploader',
  'namespace' => 'Workable\FileUploader\Http\Controllers\Api'
], function ()
{
    Route::group(['prefix'=> 'files/upload'], function($id) {
        Route::post('/', 'FileUploaderApiController@uploadFile')->name('post.api.file.upload');
        Route::post('multi', 'FileUploaderApiController@uploadFileMulti')->name('post.api.file.multi');
        Route::post('base64', 'FileUploaderApiController@uploadFromBase64')->name('post.api.file.base64');
        Route::post('link', 'FileUploaderApiController@uploadFromLink')->name('post.api.file.link');
    });

    Route::group(['prefix'=> 'images/upload'], function($id) {
        Route::post('/', 'ImageUploadApiController@uploadImage')->name('post.api.image.upload');
        Route::post('base64', 'ImageUploadApiController@uploadFromBase64')->name('post.api.image.base64');
        Route::post('multi', 'ImageUploadApiController@uploadImageMulti')->name('post.api.image.multi');
        Route::post('link', 'ImageUploadApiController@uploadFromLink')->name('post.api.image.link');
        Route::post('delete', 'ImageUploadApiController@deleteImage')->name('post.api.image.delete');
    });
});