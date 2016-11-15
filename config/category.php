<?php

return [

    /*
     * Category table used to store categories
     */
    'category_table' => 'category',

    /*
     * Category model. Update the category if it is in a different namespace.
    */
    'category' => App\Models\Category::class,
    
    /*
     * Upload path for image of category
     */
    'upload_path' => 'category',
  
];