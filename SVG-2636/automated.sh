#!/bin/sh
mysql -uroot -p --execute="DROP DATABASE streamline_local; CREATE DATABASE streamline_local";
cd /vagrant
vendor/bin/phing db:local-reset
cd /vagrant/tests/data_integrity/SVG-2636
php generate_dummy_data.php
cd /vagrant
vendor/bin/phing db:ctr-migration-full
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
