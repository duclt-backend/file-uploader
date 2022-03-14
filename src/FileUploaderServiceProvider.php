<?php

namespace Workable\FileUploader;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Workable\Base\Supports\Helper;
use Workable\Base\Traits\LoadAndPublishDataTrait;
use Workable\FileUploader\Console\ConfigFileUploaderCommand;
use Workable\FileUploader\Facades\ImageUploaderFacade;
use Workable\FileUploader\Facades\UploaderFacade;


class FileUploaderServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        Helper::autoload(__DIR__ . '/../helper');
        $this->setNamespace('packages/file-uploader')
            ->loadAndPublishConfigurations([
                'config', 'image', 'upload', 'upload_file', 'upload_image'
            ])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'api']);

        $this->commands([
            ConfigFileUploaderCommand::class
        ]);

        AliasLoader::getInstance()->alias("Uploader", UploaderFacade::class);
        AliasLoader::getInstance()->alias("ImageUploader", ImageUploaderFacade::class);
    }

    public function register()
    {
        $this->app->singleton('uploader', function () {
            return new \Workable\FileUploader\Core\Uploader\Uploader();
        });

        $this->app->singleton('image-uploader', function () {
            $uploader = App::make('uploader');
            return new \Workable\FileUploader\Core\Uploader\ImageUploader($uploader);
        });

    }
}
