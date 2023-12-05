<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $Users = [
                ['first_name' => 'User', 'last_name' => 'One', 'email' => 'user.one@mailinator.com', 'password' => bcrypt('123asd123'), 'user_type' => 1],
                ['first_name' => 'User', 'last_name' => 'two', 'email' => 'user.two@mailinator.com', 'password' => bcrypt('123asd123'), 'user_type' => 1],
                ['first_name' => 'User', 'last_name' => 'three', 'email' => 'user.three@mailinator.com', 'password' => bcrypt('123asd123'), 'user_type' => 1],
                ['first_name' => 'User', 'last_name' => 'four', 'email' => 'user.four@mailinator.com', 'password' => bcrypt('123asd123'), 'user_type' => 1],
                ['first_name' => 'User', 'last_name' => 'five', 'email' => 'user.five@mailinator.com', 'password' => bcrypt('123asd123'), 'user_type' => 1],
            ];
            User::insert($Users);
            print_r(PHP_EOL.'Test users are generated.'.PHP_EOL);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
