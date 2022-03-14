# Install
https://github.com/prometheus/prometheus#install

## Precompiled binaries
https://github.com/prometheus/prometheus#precompiled-binaries

## Docker images

You can launch a Prometheus container for trying it out with
```
docker-compose up -d
```

command: --web.enable-lifecycle --config.file=/etc/prometheus/prometheus.yml is optional. If you use --web.enable-lifecycle you can reload configuration files (e.g. rules) without restarting Prometheus:
```
curl -X POST http://0.0.0.0:9090/-/reload
```


# Reference
https://dev.to/ablx/minimal-prometheus-setup-with-docker-compose-56mp