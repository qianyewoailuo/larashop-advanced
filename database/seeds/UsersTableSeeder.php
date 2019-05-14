<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)
                ->times(10)
                ->make();

        $users = $users->makeVisible('password','remember_token')->toArray();
        User::insert($users);

        $user = User::find(1);
        $user->name = 'qianyewoailuo';
        $user->password = bcrypt('luo12345');
        $user->email = 'qianyewoailuo@126.com';
        $user->save();

        $user = User::find(2);
        $user->name = 'admin';
        $user->password = bcrypt('luo12345');
        $user->email = 'admin@admin.com';
        $user->save();
    }
}
