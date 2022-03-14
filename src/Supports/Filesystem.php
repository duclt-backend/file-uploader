<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 8/14/19
 * Time: 16:07
 */

namespace Workable\FileUploader\Supports;


class Filesystem
{
    /**
     * Make a file.
     *
     * @param $file
     * @param $content
     *
     * @throws FileAlreadyExists
     *
     * @return int
     */
    public function make($file, $content)
    {
        if ($this->exists($file)) {
            throw new \Exception('FileAlreadyExists');
        }

        return file_put_contents($file, $content);
    }
    
    /**
     * Determine if the file already exists.
     *
     * @param string $file
     *
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($file);
    }

    /**
     * Make directory.
     *
     * @param string $path
     *
     * @return void
     */
    public function makeDir($path)
    {
        if (is_dir($path))
        {
            throw new \Exception('-- Folder upload already exist');
        }
        mkdir($path, 0777, true);
    }

    /**
     * File append.
     *
     * @param string $path
     * @param string $content
     *
     * @return int
     */
    public function append($path, $content)
    {
        return file_put_contents($path, $content, FILE_APPEND | LOCK_EX);
    }
}