<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserProfilesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_profiles')->delete();
        
        \DB::table('user_profiles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'firstname' => 'Ra-ouf',
                'lastname' => 'Jumli',
                'middlename' => 'Indanan',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09171531652',
                'user_id' => 1,
                'created_at' => '2023-02-10 22:16:43',
                'updated_at' => '2023-02-10 22:16:43',
            ),
            1 => 
            array (
                'id' => 7,
                'firstname' => 'Martin',
                'lastname' => 'Wee',
                'middlename' => 'Asejo',
                'suffix' => NULL,
                'gender' => 'M',
                'mobile' => '09123456987',
                'user_id' => 7,
                'created_at' => '2023-02-10 22:28:10',
                'updated_at' => '2023-02-10 22:28:10',
            ),
            2 => 
            array (
                'id' => 8,
                'firstname' => 'Josephine',
                'lastname' => 'Nohay',
                'middlename' => 'B',
                'suffix' => NULL,
                'gender' => 'F',
                'mobile' => '09123654987',
                'user_id' => 8,
                'created_at' => '2023-02-11 11:04:56',
                'updated_at' => '2023-02-11 11:04:56',
            ),
        ));
        
        
    }
}