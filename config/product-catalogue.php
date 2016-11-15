<?php

return [

    /*
     * Product catalogue table used to store product catalogues
     */
    'product_catalogue_table' => 'product_catalogue',

    /*
     * Product Catalogue model. Update the product catalogue if it is in a different namespace.
    */
    'product_catalogue' => App\Models\ProductCatalogue::class,
    
    /*
     * Upload path for image of product catalogue
     */
    'upload_path' => 'product-catalogue',
  
];