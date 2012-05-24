<?php

function action_xml_go(&$data){
    /* 
     * Create an XML type output for some purpose 
     */
    
    // Override the default renderer - XML output is raw.
    $data['_configuration']['renderers'] = array(0=>'null');
    
    // Look at the URL that we have been asked to render and call the appropriate function
    
    switch(strtolower($_REQUEST['x'])){
        case 'sitemap.xml':
            action_xml_sitemap($data);
            break;
    }
    
    
}

function action_xml_sitemap($data){
    /*
     * Return a sitemap according to the XML Sitemap schema (http://www.sitemaps.org/protocol.html)
     */
    print '<?xml version="1.0" encoding="UTF-8"?>
                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    $urls = mysql_query('SELECT URL FROM sc_index WHERE Status = 1');
    while($url = mysql_fetch_array($urls)){
        print '<url>
                      <loc>http://' . $data['_configuration']['site']['domain'] . $url[0] . '</loc>
                      <lastmod>2005-01-01</lastmod>
                      <changefreq>daily</changefreq>
                      <priority>0.5</priority>
                   </url>';
    }
       
    print '</urlset>';
    
}

?>
