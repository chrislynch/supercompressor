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
        case 'googlemerchant.xml':
        case 'googlemerchants.xml':
            action_xml_googlemerchant($data);
            break;
    }
    
    
}

function action_xml_sitemap(&$data){
    /*
     * Return a sitemap according to the XML Sitemap schema (http://www.sitemaps.org/protocol.html)
     */
    print '<?xml version="1.0" encoding="UTF-8"?>
                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    $urls = mysql_query('SELECT URL FROM sc_index WHERE Status = 1');
    while($url = mysql_fetch_array($urls)){
        print '<url>
                      <loc>http://' . $data['_configuration']['site']['domain'] . '/' .  $url[0] . '</loc>
                      <lastmod>2005-01-01</lastmod>
                      <changefreq>daily</changefreq>
                      <priority>0.5</priority>
                   </url>';
    }
       
    print '</urlset>';
    
}

function action_xml_googlemerchant(&$data){
    print '<?xml version="1.0"?> 
            <rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"> 
            <channel>
            <title>' . $data['_configuration']['site']['name'] . '</title> 
            <link>' . $data['_configuration']['site']['domain'] . '</link>
            <description></description>';

    $products = mysql_query('SELECT ID FROM sc_index WHERE Type = "Product" AND Status = 1');
    while($product = mysql_fetch_array($products)){
        $product = data_load($product[0]);
        print '<item>';
        print _action_xml_xmlelement('g:id',$product['ID']);
        print _action_xml_xmlelement('title',$product['Title']);
        print _action_xml_xmlelement('description',$product['Body']);
        print _action_xml_xmlelement('g:google_product_category',array_drill_get('Google.Shopping.Category',$product));
        print _action_xml_xmlelement('g:product_type',array_drill_get('Category.Product',$product));
        print _action_xml_xmlelement('link',$data['_configuration']['site']['domain'] . '/' .  $product['URL']);
        print _action_xml_xmlelement('g:image_link',$data['_configuration']['site']['domain'] .  $product['Image']);
        print _action_xml_xmlelement('g:condition',array_drill_get('Google.Shopping.Condition',$product,'new'));
        print '<g:availability>in stock</g:availability>';
        
        // Pricing and Shipping
        print _action_xml_xmlelement('g:price',array_drill_get('Product.SellPrice',$product) . 'GBP');
        print '<g:shipping>
                <g:country>GB</g:country>
                <g:service>Standard Shipping</g:service>
                <g:price>' . array_drill_get('Product.ShippingPrice',$product, '0.00') . '</g:price>
               </g:shipping>';
        
        // Unique Identifiers
        print _action_xml_xmlelement('g:brand',array_drill_get('Category.Brand',$product));
        print _action_xml_xmlelement('g:mpn',array_drill_get('Product.SKU',$product));
        print '</item>';
    }
    
    print '</channel> 
        </rss>';

}
    
function _action_xml_xmlelement($elementName,$elementData){
    return '<' . $elementName . '>' . strip_tags($elementData) . '</' . $elementName . '>';
}
?>
