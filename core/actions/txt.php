<?php

function action_txt_go(&$data){
    /* 
     * Create an XML type output for some purpose 
     */
    
    // Override the default renderer - XML output is raw.
    $data['_configuration']['renderers'] = array(0=>'null');
    
    // Look at the URL that we have been asked to render and call the appropriate function
    
    switch(strtolower($_REQUEST['x'])){
        case 'robots.txt':
            action_txt_robots($data);
            break;
    }
    
    
}

function action_txt_robots($data){
    /*
     * Return a sitemap according to the XML Sitemap schema (http://www.sitemaps.org/protocol.html)
     */
    print '
User-agent: *
Disallow: 
Disallow: /cgi-bin/
Sitemap: http://www.solarpanelcentric.co.uk/sitemap.xml';
    
}

?>
