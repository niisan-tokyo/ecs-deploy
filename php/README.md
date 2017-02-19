# ECS deploy with php

Before using deploy.php, you need to run `composer install` command.

```
composer install
```

You may need to set following environment variables:

- AWS_ACCESS_KEY_ID
- AWS_SECRET_ACCESS_KEY

# Usage

Usage is following:

```
php deploy.php -c <CLUSTER_NAME> -s <SERVICE_NAME> -n <IMAGE_NAMESPACE> -f <FAMILY> -t <TAG_NAME> -r <REGION_NAME>
```
We can set more than one parameters in FAMILY field.

We can also set environment variables:

- CLUSTER_NAME
- SERVICE_NAME
- IMAGE_NAMESPACE
- TAG_NAME
- REGION_NAME

But we must set family field in command options.
