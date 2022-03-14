# File Uploader

File upload with local and s3 (Minio)

## Cấu hình

1. PHP >= 7.1
2. core-packages/core: `composer require core-packages/core`
3. core-packages/helper: `composer require core-packages/helper`
4. intervention/image:^2.5: `composer require intervention/image:^2.5`
5. league/flysystem-aws-s3-v3: `composer require league/flysystem-aws-s3-v3`
6. Extensions php ready: `imagick`

### Cài đặt

```
# Add submodule
    git submodule add git@gitlab.com:core-packages-group/file-uploader.git  core-packages/file-uploader

# Init repository in composer.json
  "repositories": [
        {
            "type": "path",
            "url": "./platform/packages/*",
            "options": {
                "symlink": true
            }
        }
    ]

# Install composer
    composer require core-packages/file-uploader:@dev

# Publish package
    php artisan vendor:publish --provider="Workable\FileUploader\FileUploaderServiceProvider" --tag="config"
```

## Config trước khi upload

```
# Config file config/filesystems.php

[
	'public' => [
	    'driver' => 'local',
	    'root' => public_path(),
	    'url' => env('APP_URL').'/storage',
	    'visibility' => 'public',
	],

	'minio' => [
	    'driver'                  => 's3',
	    'endpoint'                => env('MINIO_ENDPOINT', 'https://cdn.123job.vn/'),
	    'use_path_style_endpoint' => true,
	    'key'                     => env('AWS_KEY'),
	    'secret'                  => env('AWS_SECRET'),
	    'region'                  => env('AWS_REGION'),
	    'bucket'                  => env('AWS_BUCKET','123job'),
	    'url'                     => env('MINIO_ENDPOINT', 'https://cdn.123job.vn/')
	]
]

# Config upload demo by type upload
'pdf_and_doc'    => [
    'extensions'     => ['doc', 'docx', 'pdf', 'xlsx'],
    'file_size'      => 1024 * 3,
    'upload_folder'  => 'upload_files',
    'check_resize'      => 0,
]

```

**Các thông số**

- `extensions`: Các định dạng cho phép upload
- `file_size`: kích thước file được upload tính bằng KB
- `upload_folder`: folder chứa file uploaders
- `check_resize`: Kiểm tra cho phép resize nếu định dạng width vs height k khớp. Mặc định là 0
- `thumbs`: Các thumbs sẽ được resize
- `logo` vs `pdf_and_doc` : Các key sẽ được sử dụng cho upload cụ thể chức năng gì.

## Cách sử dụng upload hình ảnh: Jpg, Png, Jpeg

```
    # via service container
    app("image-uploader")->setConfig($config)->upload($fileControl)

    # via facade
    use ImageUploader;
    ImageUploader::setConfig($config)->upload($fileControl);
```

| Functions      | Description                                                       | Param                                                 |
| -------------- | ----------------------------------------------------------------- | ----------------------------------------------------- |
| upload         | Cho phép upload một hình ảnh                                      | $fileControl, $arrayThumbs = [], $optional = 'resize' |
| uploadMulti    | Cho phép upload nhiều hình ảnh                                    | $fileControl, $arrayThumbs = [], $optional = 'resize' |
| uploadFromLink | Cho phép upload một hình ảnh từ đường dẫn link hình ảnh           | $link, $arrayThumbs = [], $optional = 'resize'        |
| uploadBase64   | Cho phép upload hình ảnh là base64. Thường áp dụng cho upload jax | $fileBase64, $arrayThumbs = [], $optional = 'resize'  |

Ghi chú: Các function trên khi upload sẽ chèn các tham số vào. Các tham số này là:

- `$fileControl` : Tên thuộc tính `name` của input type=file
- `$arrayThumbs` : mảng thumb truyền vào. Xem thêm config upload image
- `$optional` : Cho phép resize hình ảnh hay không nếu fit không phù hợp

## Cách sử dụng upload bằng file: pdf, docs, .xls

```
    # via service container
    app("uploader")->setConfig($config)->upload($fileControl)

    # via facade
    use Uploader;
    Uploader::setConfig($config)->upload($fileControl);
```

| Functions      | Description                                 | Param              |
| -------------- | ------------------------------------------- | ------------------ |
| upload         | Cho phép upload một file                    | $fileControl       |
| uploadMulti    | Cho phép upload nhiều files                 | $fileControl       |
| uploadFromLink | Cho phép upload một files từ đường dẫn link | $link, $param = [] |

Ghi chú: Các function trên khi upload sẽ chèn các tham số vào. Các tham số này là:

- `$fileControl` : Tên thuộc tính `name` của input type=file

## Cách parse đường dẫn để lấy thông tin url

```
    $url = 'md_2021_02_11______07c2c9771351edf9faabfec65a32a415.jpg';
    $link = parse_url_file($url,'uploads');
```

**Ghi chú**:

- Lưu ý config: packages/file-uploader/upload - `static_url` Chứa config url nối

## Description feature ready

| Support                                     | Driver Local | Driver Minio |
| ------------------------------------------- | ------------ | ------------ |
| Upload                                      | [x]          | [x]          |
| Base 64 encode                              | [x]          | [x]          |
| Upload from link                            | [x]          | [x]          |
| Upload Multiple                             | [x]          | [x]          |
| Format: 'jpg', 'jpeg', 'png', 'gif', 'webp' | [x]          | [x]          |
| Not enable: css,js, php.                    | [x]          | [x]          |
| Limit size                                  | [x]          | [x]          |
| Resize thumb                                | [x]          | [x]          |
| Crop thumb                                  | [x]          | [x]          |
| Upload link webp: upload/crop/resize        | [x]          | [x]          |
| convert link jpg/png/jpeg/gif to webp       | [x]          | [x]          |
| Watermark                                   | []           | []           |

# Todo task

- [] Upload nhiều server
- [] Parse Url ra thành nhiều server.

# Testing

Cấu hình file sau vào file phpunit.xml của dự án để có thể chạy testcase.

```
<testsuite name="UploadFile">
    <directory suffix=".php">./platform/packages/file-uploader/tests/Feature</directory>
</testsuite>
```

Run testcase cho toàn bộ upload files.

```
./vendor/bin/phpunit --testsuite=UploadFile --testdox
```