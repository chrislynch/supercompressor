<?php

print_r($argv);

if (isset($argv[2])){
    $_SERVER['HTTP_HOST'] = $argv[1];
}

include 'bootstrap.php';
// include 'core/actions/install/import.php';
include 'core/actions/' . $argv[2] . '.php';

$function = 'action_' . str_ireplace('/','_',$argv[2]) . '_go';
$parameters = array( &$data );
call_user_func_array($function,$parameters);

// action_install_import_go($data);

?>
