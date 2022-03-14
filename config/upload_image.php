<?php
return [
    'banner'    => [
        'extensions'     => ['jpg', 'jpeg', 'png','gif'],
        'file_size'      => 1024 * 3,
        'upload_folder'  => 'uploads',
        'check_resize'      => 0,
    ],
    'logo'     => [
        'extensions'     => ['jpg', 'jpeg', 'png', 'gif'],
        'file_size'      => 480,
        'upload_folder'  => 'uploads',
        'check_resize'      => 0,
        'thumbs'          => [
            'sm_' => ['width' => 320, 'height' => 240]
        ]
    ]
];