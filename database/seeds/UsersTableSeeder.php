<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Add admin
        DB::table('user')
            ->updateOrInsert([
                'account' => 'admin',
            ], [
                'password' => Hash::make('admin'),
                'sn' => 'seeder',
                'role'=>'A',
                'name' => '管理員',
                'sign_up_datetime' => date('Y-m-d H:i:s'),
                'birthday' => '2000-01-01',
                'gender' => '男',
                'email' => 'asdf@qwer.zxcv',
                'enable' => 1
            ]);

        //Add business
        DB::table('user')
            ->updateOrInsert([
                'account' => 'b',
            ], [
                'password' => Hash::make('b'),
                'sn' => 'seeder',
                'role'=>'B',
                'name' => '商人',
                'sign_up_datetime' => date('Y-m-d H:i:s'),
                'birthday' => '2000-01-01',
                'gender' => '男',
                'email' => 'asdf@qwer.zxcv',
                'enable' => 1
            ]);

        //Add custom
        DB::table('user')
            ->updateOrInsert([
                'account' => 'c',
            ], [
                'password' => Hash::make('c'),
                'sn' => 'seeder',
                'role'=>'C',
                'name' => '客人',
                'sign_up_datetime' => date('Y-m-d H:i:s'),
                'birthday' => '2000-01-01',
                'gender' => '男',
                'email' => 'asdf@qwer.zxcv',
                'enable' => 1
            ]);

    }
}
