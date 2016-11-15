<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder
{
    public function run()
    {
        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::table(config('access.users_table'))->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.users_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.users_table') . ' CASCADE');
        }

        //Add the master administrator, user id of 1
        $users = [
            [
                'name'              => 'Admin Honsen',
                'email'             => 'admin' . config('variables.company_email'),
                'password'          => bcrypt(openssl_digest('123456', 'sha512')),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
//            [
//                'name'              => 'Backend User',
//                'email'             => 'executive' . config('variables.company_email'),
//                'password'          => bcrypt(openssl_digest('123456', 'sha512')),
//                'confirmation_code' => md5(uniqid(mt_rand(), true)),
//                'confirmed'         => true,
//                'created_at'        => Carbon::now(),
//                'updated_at'        => Carbon::now(),
//            ],
            [
                'name'              => 'Default User',
                'email'             => 'user' . config('variables.company_email'),
                'password'          => bcrypt(openssl_digest('123456', 'sha512')),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table(config('access.users_table'))->insert($users);

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}