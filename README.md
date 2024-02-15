# Currency converter

Description

- swagger: http://127.0.0.1:8080/docs/ ([api.openapi.yaml](/swagger/api.openapi.yaml))

## Requirements for host

* docker
* make

## Service usage

Run `make up` to prepare and run service.

Run `make test` to run tests.

### Swagger UI

API documentation can be placed in ./swagger/api.openapi.yaml.
Url for Swagger UI: `/docs/` prefixed with host and port of nginx container,
i.e.: http://127.0.0.1:8080/docs/

### API auth with fixed tokens

For example: `Authorization: Bearer your-token` where 'your-token' is token
which should match with environment variable `API_AUTH_TOKEN`. 
Default value is `7FTloGfDWuVTw04zVRZ2Hd9Se3FYDa9xRsOo59Kz34KzDPAPcTDGdHC1BaGpQjys`
