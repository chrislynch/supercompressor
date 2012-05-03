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
}

?>