<?php

namespace App\Console\Commands;

use App\Models\Clients;
use Illuminate\Console\Command;

class GenerateClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-clients';

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
            $Clients = [
                ['user_id' => '1', 'name' => 'Client One', 'email' => 'client.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '1', 'name' => 'Client Two', 'email' => 'client.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'name' => 'Client One', 'email' => 'client.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'name' => 'Client Two', 'email' => 'client.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'name' => 'Client One', 'email' => 'client.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'name' => 'Client Two', 'email' => 'client.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'name' => 'Client One', 'email' => 'client.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'name' => 'Client Two', 'email' => 'client.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'name' => 'Client One', 'email' => 'client.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'name' => 'Client Two', 'email' => 'client.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
            ];
            Clients::insert($Clients);
            print_r(PHP_EOL.'Test clients are generated.'.PHP_EOL);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
