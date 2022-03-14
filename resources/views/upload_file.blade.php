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
                        <span>Select file to upload:</span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('post.file.upload') }}" method="post"  enctype="multipart/form-data">
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
                        <span>Select multi file to upload:</span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('post.file.upload_multi') }}" method="post"  enctype="multipart/form-data">
                            <input type="file" name="file[]" multiple class="form-control"> <br>
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