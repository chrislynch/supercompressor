<?php

function datatype_product_load(&$data){
    // Load additional data into the item.
    
    // Was Price and Saving.
    if (array_drill_get('Product.ListPrice', $data) <= array_drill_get('Product.SellPrice', $data)){
        array_drill_set('Product.WasPrice',0.00,$data);
        array_drill_set('Product.Saving',0.00,$data);
    } else {
        array_drill_set('Product.WasPrice', array_drill_get('Product.ListPrice',$data), $data);
        array_drill_set('Product.Saving',round(array_drill_get('Product.ListPrice', $data) - array_drill_get('Product.SellPrice', $data),2),$data);
    }
    
    // Tidy up number formattings
    array_drill_set('Product.ListPrice',number_format(array_drill_get('Product.ListPrice',$data),2),$data);
    array_drill_set('Product.SellPrice',number_format(array_drill_get('Product.SellPrice',$data),2),$data);
    array_drill_set('Product.WasPrice',number_format(array_drill_get('Product.WasPrice',$data),2),$data);
    array_drill_set('Product.Saving',number_format(array_drill_get('Product.Saving',$data),2),$data);
    
    // We should have a default image
    $image = array_drill_get('Image',$data);
    if (strlen($image) == 0){
        // array_drill_set('Image','/core/templates/default/images/error.jpg',$data);
        array_drill_set('Image','/sites/www.pestcontrolcentric.co.uk/images/' . array_drill_get('Product.SKU',$data) . '.jpg',$data);
    }
}

?>
