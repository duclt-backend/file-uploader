<?php
Route::group([
  'prefix' => 'file-uploader',
  'namespace' => 'Workable\FileUploader\Http\Controllers'
], function () {

    Route::get('/', 'FileUploaderController@index');

    Route::group(['prefix' => 'image'], function () {
        Route::get('/upload', 'ImageUploaderController@getUploadImage')->name('get.image.upload');
        Route::post('/upload', 'ImageUploaderController@postUploadImage')->name('post.image.upload');
        Route::post('/upload-base64', 'ImageUploaderController@postUploadBase64')->name('post.image.upload_base64');
        Route::post('/upload-link', 'ImageUploaderController@postUploadLink')->name('post.image.upload_link');
        Route::post('/upload-multi', 'ImageUploaderController@postUploadImageMulti')->name('post.image.upload_multi');
        Route::get('/convert', 'ImageUploaderController@convert')->name('post.image.convert');
        Route::post('/delete', 'ImageUploaderController@deleteImage')->name('post.image.delete');
    });

    Route::group(['prefix' => 'files'], function () {
        Route::get('/upload', 'FileUploaderController@getUpload')->name('get.file.upload');
        Route::post('/upload', 'FileUploaderController@postUpload')->name('post.file.upload');
        Route::post('/upload-multi', 'FileUploaderController@postUploadMulti')->name('post.file.upload_multi');
        Route::post('/upload-base64', 'FileUploaderController@postUploadBase64')->name('post.file.upload_base64');
    });

});
