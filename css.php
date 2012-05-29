<?php

if (isset($_GET['css'])){
    // Get the file, as per the $_GET, and run it through the rendering process
    // This enables us to have CSS variables for colour and font.
    header('Content-type: text/css');
    include 'bootstrap.php';
    print array_drill_get('_configuration.css.imports',$data);
    print render_template($_GET['css'], $data);
    
}

?>
