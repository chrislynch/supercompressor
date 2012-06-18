<?php
function action_403_go(&$data){
    header("HTTP/1.0 403 Forbidden");
    header("Status: 403 Forbidden");
    exit;
}
?>
