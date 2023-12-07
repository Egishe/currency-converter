# test_app_mel

Description

- swagger: http://127.0.0.1:3928080/docs/ ([api.openapi.yaml](/swagger/api.openapi.yaml))

## Requirements for host

* docker
* make
* php 8.2

## Service usage

Run `make up` to prepare and run service.

Run `make test` to run tests.

Other useful commands:
* make lint
* make lint-fix
* make st

### Swagger UI

API documentation can be placed in ./swagger/api.openapi.yaml.
Url for Swagger UI: `/docs/` prefixed with host and port of nginx container,
i.e.: http://127.0.0.1:39280/docs/

### API auth with fixed tokens

For example: `Authorization: Bearer XCDQ5ERT3NFST2IF` where 'XCDQ5ERT3NFST2IF' is token
which should match with environment variable `API_AUTH_TOKEN`.
