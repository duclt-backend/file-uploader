# Install test
Cài đặt test trong file `phpunit.xml`

```
<testsuite name="UploadFile">
    <directory suffix=".php">./platform/packages/file-uploader/tests/Feature</directory>
</testsuite>
```


# Run first
```
# Test all test case
./vendor/bin/phpunit --testsuite=UploadFile

# Test class 
./vendor/bin/phpunit platform/packages/file-uploader/tests/Feature/ImageUploaderFileUploaderTest.php
```

