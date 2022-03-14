<?php

return [
    // Các file được phép upload
    'extensions'    => ['gif', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'pdf', 'bmp', 'js', 'css', 'webp'],
    'file_size'     => 2048,
    'upload_folder' => 'uploads',
    'static_url'    => env('URL_STATIC_FILE', 'https://cdn.123job.vn/'),
    'default'       => env('DRIVER_UPLOAD', 'local'),
    'driver'        => [
        'local' => [
            'disk_name'        => \Workable\FileUploader\Core\Enum\UploadEnum::DISK_PUBLIC,
            'root'             => base_path(),
            'path'             => '',
            'url'              => '',
        ],
        'minio'   => [
            'disk_name'               => \Workable\FileUploader\Core\Enum\UploadEnum::DISK_MINIO,
            'use_path_style_endpoint' => true,
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_REGION'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_ENDPOINT', 'https://cdn.123job.vn/'),
            'endpoint'                => env('AWS_ENDPOINT', 'https://cdn.123job.vn/'),
            'path'                    => '',
            'root'                    => '/'
        ]
    ]
];
