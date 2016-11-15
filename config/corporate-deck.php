<?php

return [

    /*
     * Corporate Deck table used to store corporate decks
     */
    'corporate_deck_table' => 'corporate_deck',

    /*
     * Category model. Update the cateogry if it is in a different namespace.
     */
    'corporate_deck' => App\Models\CorporateDeck::class,
    
    /*
     * Upload path for pdf of corporate deck
     */
    'pdf_upload_path' => 'corporate-deck',
    
  
];