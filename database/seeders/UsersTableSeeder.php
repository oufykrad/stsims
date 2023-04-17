<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'administrator',
                'email' => 'kradjumli@gmail.com',
                'email_verified_at' => '2023-02-10 22:16:43',
                'password' => '$2y$10$8hJqG/9D.B7/5INfRHuKx.WM4bU98mAVoQcfEWzp4dVJ/hBXveHMq',
                'avatar' => 'administrator.png',
                'role' => 'Administrator',
                'is_active' => 0,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-02-10 22:16:43',
                'updated_at' => '2023-03-21 11:00:38',
            ),
            1 => 
            array (
                'id' => 7,
                'username' => 'maw0302',
                'email' => 'dostisko@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$kpZeLlunC/MPQ2E2wi86NeoXhoqvKH1alPrsbOikadlIVTXTQ0OoW',
                'avatar' => 'maw0302.png',
                'role' => 'Regional Director',
                'is_active' => 0,
                'welcome_valid_until' => '2023-02-12 18:25:56',
                'remember_token' => NULL,
                'created_at' => '2023-02-10 22:28:10',
                'updated_at' => '2023-02-11 18:25:56',
            ),
            2 => 
            array (
                'id' => 8,
                'username' => 'jbn0123',
                'email' => 'josephine@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$VE/seCpAgCf9/5Li80PImek2/4XvKq/kGlROMZOdK3zC4aFY1BzG.',
                'avatar' => 'jbn0123.png',
                'role' => 'Scholarship Coordinator',
                'is_active' => 1,
                'welcome_valid_until' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-02-11 11:04:56',
                'updated_at' => '2023-02-11 11:04:56',
            ),
        ));
        
        
    }
}