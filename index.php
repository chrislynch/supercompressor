<?php

/*
 * Supercompressor - eCommerce.
 */

include 'bootstrap.php';

/*
 * Decide what action we are doing and then do it
 */
if (isset($_REQUEST['action'])){
    // We have requested a specific action
    $data['_configuration']['actions'][0] = $_REQUEST['action'];
} elseif (isset($_REQUEST['x'])){
    // We are looking for a specific action based on URL.
    $data['_configuration']['actions'][0] = url_action($_REQUEST['x'], $data);
} else {
    // No URL defined, so we are on the home page
    $data['_configuration']['actions'][0] = 'home';
}

ksort($data['_configuration']['actions']);

foreach($data['_configuration']['actions'] as $action){
    if (file_exists(array_drill_get('_configuration.site.domaindir',$data) . 'actions/' . $action . '.php')){
        include_once array_drill_get('_configuration.site.domaindir',$data) . 'actions/' . $action . '.php';
    } else {
        include_once 'core/actions/' . $action . '.php';
    }
    
    $parameters = array( &$data );
    call_user_func_array('action_' . str_ireplace('/', '_', $action)  . '_go',$parameters);
}

/*
 * All of our transacting is now done. Close the link to the database.
 */
mysql_close($db);

/*
 * Render the page
 */
$return = '';
$return .= render_template($dir_template . 'html-header.html',$data);
$return .= render_template($dir_template . 'page-header.html',$data);
$return .= render_template($dir_template . 'sidebar-left.html',$data);
$return .= render_template($dir_template . 'content-header.html',$data);

if (isset($data['templates']) && sizeof($data['templates']) > 0){
    foreach($data['templates'] as $template){
        $return .= render_template($dir_template . $template . '.html',$data);
    }
} else {
    $return .= render_template($dir_template . 'actions/' . $data['_configuration']['actions'][0] . '.html',$data);
}

// $return .= render_template($dir_template . 'content.html',$data);

$return .= render_template($dir_template . 'content-footer.html',$data);
$return .= render_template($dir_template . 'sidebar-right.html',$data);
$return .= render_template($dir_template . 'page-footer.html',$data);
$return .= render_template($dir_template . 'html-footer.html',$data);

print $return;

if (isset($_GET['debug'])){
    print '<hr><pre>' . print_r($data,TRUE) . '</pre>';
}


/*
 * END OF SUPERCOMPRESSOR
 */


?>
