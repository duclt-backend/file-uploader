<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>Select image to upload:</span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('post.image.upload') }}" method="post"  enctype="multipart/form-data">
                            <input type="file" name="file" class="form-control"> <br>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Upload Image" name="submit">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>Select multi image to upload:</span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('post.image.upload_multi') }}" method="post"  enctype="multipart/form-data">
                            <input type="file" name="file[]" multiple class="form-control"> <br>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Upload Image" name="submit">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>Input link image:</span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('post.image.upload_link') }}" method="post"  enctype="multipart/form-data">
                            <textarea name="link" rows="5" class="form-control" placeholder="Nhập link cần tải"></textarea>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Upload Image" name="submit">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>Input base64 image: <a target="_blank" href="https://imagetobase64.io/">https://imagetobase64.io/</a></span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('post.image.upload_base64') }}" method="post"  enctype="multipart/form-data">
                            <textarea name="base64" rows="5" class="form-control" placeholder="Nhập image base64"></textarea>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Upload Image" name="submit">
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>