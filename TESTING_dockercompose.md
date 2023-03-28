Docker Compose Drupal 9 Full option  - php8, nginx, mariadb, solr, redis
========================================================================

This is a docker-compose version of the Lagoon-example tests:

Start up tests
--------------

Run the following commands to get up and running with this example.

```bash
# Should remove any previous runs and poweroff
sed -i -e "/###/d" docker-compose.yml
docker network inspect amazeeio-network >/dev/null || docker network create amazeeio-network
docker-compose down

# Should start up our Lagoon Drupal 9 site successfully
docker-compose build && docker-compose up -d

# Ensure mariadb pod is ready to connect
docker run --rm --net demo-polysite-webinar-2023_default amazeeio/dockerize dockerize -wait tcp://mariadb:3306 -timeout 1m
```

Verification commands
---------------------

Run the following commands to validate things are rolling as they should.

```bash
# Should be able to site install via Drush
docker-compose exec -T cli bash -c "drush si -y"
docker-compose exec -T cli bash -c "drush cr -y"
docker-compose exec -T cli bash -c "drush status" | grep "Drupal bootstrap" | grep "Successful"

# Should have all the services we expect
docker ps --filter label=com.docker.compose.project=demo-polysite-webinar-2023 | grep Up | grep demo-polysite-webinar-2023_nginx_1
docker ps --filter label=com.docker.compose.project=demo-polysite-webinar-2023 | grep Up | grep demo-polysite-webinar-2023_mariadb_1
docker ps --filter label=com.docker.compose.project=demo-polysite-webinar-2023 | grep Up | grep demo-polysite-webinar-2023_php_1
docker ps --filter label=com.docker.compose.project=demo-polysite-webinar-2023 | grep Up | grep demo-polysite-webinar-2023_cli_1
docker ps --filter label=com.docker.compose.project=demo-polysite-webinar-2023 | grep Up | grep demo-polysite-webinar-2023_solr_1

# Should ssh against the cli container by default
docker-compose exec -T cli bash -c "env | grep LAGOON=" | grep cli-drupal

# Should have the correct environment set
docker-compose exec -T cli bash -c "env" | grep LAGOON_ROUTE | grep demo-polysite-webinar-2023.docker.amazee.io
docker-compose exec -T cli bash -c "env" | grep LAGOON_ENVIRONMENT_TYPE | grep development

# Should be running PHP 8
docker-compose exec -T cli bash -c "php -v" | grep "PHP 8"

# Should have composer
docker-compose exec -T cli bash -c "composer --version"

# Should have php cli
docker-compose exec -T cli bash -c "php --version"

# Should have drush
docker-compose exec -T cli bash -c "drush --version"

# Should have npm
docker-compose exec -T cli bash -c "npm --version"

# Should have node
docker-compose exec -T cli bash -c "node --version"

# Should have yarn
docker-compose exec -T cli bash -c "yarn --version"

# Should have a running Drupal 9 site served by nginx on port 8080
docker-compose exec -T cli bash -c "curl -kL http://nginx:8080" | grep "Drush Site-Install"

# Should have a "drupal" Solr core
docker-compose exec -T cli bash -c "curl solr:8983/solr/admin/cores?action=STATUS\&core=drupal"

# Should be able to reload "drupal" Solr core
docker-compose exec -T cli bash -c "curl solr:8983/solr/admin/cores?action=RELOAD\&core=drupal"

# Check Solr has 8.x config in "drupal" core
docker-compose exec -T solr bash -c "cat /var/solr/data/drupal/conf/schema.xml | grep solr-8.x"

# redis-6 Should be running Redis v6.0
docker-compose exec -T redis sh -c "redis-server --version" | grep "v=6."

# redis-6 Should be able to see Redis databases
docker-compose exec -T redis sh -c "redis-cli CONFIG GET databases"

# redis-6 databases should be populated
docker-compose exec -T redis sh -c "redis-cli dbsize" | grep -Ev "^0$"

# Should be able to db-export and db-import the database
docker-compose exec -T cli bash -c "drush sql-dump --result-file /app/test.sql"
docker-compose exec -T cli bash -c "drush sql-drop -y"
docker-compose exec -T cli bash -c "drush sql-cli < /app/test.sql"
docker-compose exec -T cli bash -c "rm test.sql*"

# Should be able to show the drupal tables
docker-compose exec -T cli bash -c "drush sqlq \'show tables;\'" | grep users

# Should be able to rebuild and persist the database
docker-compose build && docker-compose up -d
docker-compose exec -T cli bash -c "drush sqlq \'show tables;\'" | grep users
```

Destroy tests
-------------

Run the following commands to trash this app like nothing ever happened.

```bash
# Should be able to destroy our Drupal 9 site with success
docker-compose down --volumes --remove-orphans
```
