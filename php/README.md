# ECS deploy with php

Before using deploy.php, you need to run `composer install` command.

```
composer install
```

You may need to set following environment valuables:

- AWS_ACCESS_KEY_ID
- AWS_SECRET_ACCESS_KEY

# Usage

Usage is following:

```
php deploy.php -c <CLUSTER_NAME> -s <SERVICE_NAME> -i <IMAGE_NAME> -t <TAG_NAME> -r <REGION_NAME>
```

Or we can set environment valuables:

- CLUSTER_NAME
- SERVICE_NAME
- IMAGE_NAME
- TAG_NAME
- REGION_NAME
