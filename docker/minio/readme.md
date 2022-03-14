- [Deploy MinIO on Docker Compose](https://docs.min.io/docs/deploy-minio-on-docker-compose.html)
# Run Distributed MinIO on Docker Compose

GNU/Linux and macOS
```
Copydocker-compose pull
docker-compose up
```

Windows

```
docker-compose.exe pull
docker-compose.exe up
```

Distributed instances are now accessible on the host at ports 9000, proceed to access the Web browser at http://127.0.0.1:9000/. Here 4 MinIO server instances are reverse proxied through Nginx load balancing.

**Notes*
- By default the Docker Compose file uses the Docker image for latest MinIO server release. You can change the image tag to pull a specific MinIO Docker image.
- There are 4 minio distributed instances created by default. You can add more MinIO services (up to total 16) to your MinIO Compose deployment. To add a service
    - Replicate a service definition and change the name of the new service appropriately.
    - Update the command section in each service.
    - Add a new MinIO server instance to the upstream directive in the Nginx configuration file.

Read more about distributed MinIO [here](https://docs.min.io/docs/distributed-minio-quickstart-guide).




# MinIO Client Quickstart Guide
```
alias       set, remove and list aliases in configuration file
ls          list buckets and objects
mb          make a bucket
rb          remove a bucket
cp          copy objects
mirror      synchronize object(s) to a remote site
cat         display object contents
head        display first 'n' lines of an object
pipe        stream STDIN to an object
share       generate URL for temporary access to an object
find        search for objects
sql         run sql queries on objects
stat        show object metadata
mv          move objects
tree        list buckets and objects in a tree format
du          summarize disk usage recursively
retention   set retention for object(s)
legalhold   set legal hold for object(s)
diff        list differences in object name, size, and date between two buckets
rm          remove objects
encrypt    manage bucket encryption config
event       manage object notifications
watch       listen for object notification events
undo        undo PUT/DELETE operations
policy      manage anonymous access to buckets and objects
tag         manage tags for bucket(s) and object(s)
ilm         manage bucket lifecycle
version     manage bucket versioning
replicate   configure server side bucket replication
admin       manage MinIO servers
update      update mc to latest release
```

##Docker Container
**Stable**
```
docker pull minio/mc
docker run minio/mc ls play
```

**Edge**
```
docker pull minio/mc:edge
docker run minio/mc:edge ls play
```
