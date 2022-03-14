<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2020/08/18 - 10:42
 */
namespace Workable\FileUploader\Console;
use Illuminate\Console\Command;
use Workable\FileUploader\Supports\FileUploaderConfigSupport;

class ConfigFileUploaderCommand extends Command
{
    protected $signature = 'file-uploader:config';
    protected $description = 'Get config file uploader';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        var_dump(FileUploaderConfigSupport::get());
    }

}