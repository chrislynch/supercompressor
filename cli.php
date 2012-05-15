<?php

print_r($argv);

if (isset($argv[1])){
    $_SERVER['HTTP_HOST'] = $argv[1];
}

include 'bootstrap.php';
include 'core/actions/install/import.php';

action_install_import_go($data);

?>
