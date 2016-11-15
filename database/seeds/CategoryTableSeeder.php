<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::table(config('category.category_table'))->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM ' . config('category.category_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('category.category_table') . ' CASCADE');
        }

        /*
         * Get admin user
         */
        $user_model = config('auth.providers.users.model');
        $user_model = new $user_model;
        $admin_object = $user_model::first();
        
        /**
         * Category create
         */
        $category_model            = config('category.category');
        $category                  = new $category_model;
        $category->name            = 'Label Printing';
        $category->user_id         = $admin_object->id;
        $category->created_at      = Carbon::now();
        $category->updated_at      = Carbon::now();
        $category->save();
        
        $category_model            = config('category.category');
        $category                  = new $category_model;
        $category->name            = 'Screen Printing';
        $category->user_id         = $admin_object->id;
        $category->created_at      = Carbon::now();
        $category->updated_at      = Carbon::now();
        $category->save();
        
        $category_model            = config('category.category');
        $category                  = new $category_model;
        $category->name            = 'Functional Printing';
        $category->user_id         = $admin_object->id;
        $category->created_at      = Carbon::now();
        $category->updated_at      = Carbon::now();
        $category->save();
        
        $category_model            = config('category.category');
        $category                  = new $category_model;
        $category->name            = 'Precision Diecutting';
        $category->user_id         = $admin_object->id;
        $category->created_at      = Carbon::now();
        $category->updated_at      = Carbon::now();
        $category->save();
        
        $category_model            = config('category.category');
        $category                  = new $category_model;
        $category->name            = 'Others';
        $category->user_id         = $admin_object->id;
        $category->created_at      = Carbon::now();
        $category->updated_at      = Carbon::now();
        $category->save();

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}