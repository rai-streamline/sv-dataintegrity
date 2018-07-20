#!/bin/sh


echo -n "Running this script will affect the data on the target database. Are you sure you want to do this(yes/no)? "
read answer
if [ "$answer" != "${answer#yes}" ] ;then
    php migrate_to_check_employee_credentials.php && php migrate_to_license_requests_1_license_request_params.php && php migrate_to_license_requests_2_license_requests.php && php migrate_to_license_requests_3_license_results_data.php && php migrate_to_license_requests_4_license_results.php && php migrate_to_license_requests_5_license_results_images.php && php migrate_to_license_requests_6_update_license_results_data.php && php migrate_to_license_requests_7_license_results_resolutions.php && php migrate_to_npi_requests_1_npi_requests.php && php migrate_to_npi_requests_2_npi_results_data.php && php migrate_to_npi_requests_3_npi_results.php && php migrate_to_npi_requests_4_npi_results_resolutions.php
else
    echo Aborting...
fi