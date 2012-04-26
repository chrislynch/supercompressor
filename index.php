<?php

/*
 * Supercompressor - eCommerce.
 */

include 'bootstrap.php';

/*
 * Decide what action we are doing and then do it
 */
if (isset($_REQUEST['action'])){
    $data['actions'][0] = $_REQUEST['action'];
} else {
    $data['actions'][0] = 'home';
}

ksort($data['actions']);

foreach($data['actions'] as $action){
    if (file_exists($dir_site . '/actions/' . $action . '.php')){
        include_once $dir_site . '/actions/' . $action . '.php';
    } else {
        include_once 'actions/' . $action . '.php';
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
$return .= render_template($dir_template . 'sidebar-left-1.html',$data);
$return .= render_template($dir_template . 'sidebar-left-2.html',$data);
$return .= render_template($dir_template . 'content-header.html',$data);

// $return = render_template($dir_template . $action . '.html',$data);
$return .= render_template($dir_template . 'content.html',$data);

$return .= render_template($dir_template . 'content-footer.html',$data);
$return .= render_template($dir_template . 'sidebar-right-1.html',$data);
$return .= render_template($dir_template . 'sidebar-right-2.html',$data);
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
