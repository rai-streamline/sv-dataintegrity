#!/bin/sh
mysql -uroot -p --execute="DROP TABLE streamline_local.DATABASECHANGELOGLOCK; DROP TABLE streamline_local.DATABASECHANGELOG";
cd /vagrant/tests/data_integrity/SVG-2636
php generate_dummy_data.php
cd /vagrant
vendor/bin/liquibase --changeLogFile=etc/database/migrations/ctr/changelogs/simulate_changelog.xml --defaultsFile=liquibase.properties --contexts=production update
cd /vagrant/tests/data_integrity/SVG-2636/validation
php rollout.php
cd /vagrant/tests/data_integrity/SVG-2636
php generate_incremental_dummy_data.php
cd /vagrant
vendor/bin/phing db:ctr-migration-incremental
cd /vagrant/tests/data_integrity/SVG-2636/validation
php rollout.php
cd /vagrant/tests/data_integrity/SVG-2636
php generate_rollback_data.php
cd /vagrant
vendor/bin/phing db:ctr-migration-rollback
cd /vagrant/tests/data_integrity/SVG-2636/validation
php rollback.php
cd /vagrant/tests/data_integrity/SVG-2636
