<?php
function action_404_go(&$data){
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    exit;
}
?>
