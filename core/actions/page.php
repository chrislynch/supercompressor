<?php

function action_page_go(&$data){
    if (!isset($data['templates'])){ $data['templates'] = array();}
    $data['templates'][0] = 'pages/' . $_GET['page'];
}