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
                ['user_id' => '1', 'invoice_prefix' => 'CCO', 'name' => 'Client One', 'email' => 'client1.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '1', 'invoice_prefix' => 'CCT', 'name' => 'Client Two', 'email' => 'client1.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'invoice_prefix' => 'CCO', 'name' => 'Client One', 'email' => 'client2.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'invoice_prefix' => 'CCT', 'name' => 'Client Two', 'email' => 'client2.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'invoice_prefix' => 'CCO', 'name' => 'Client One', 'email' => 'client3.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'invoice_prefix' => 'CCT', 'name' => 'Client Two', 'email' => 'client3.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'invoice_prefix' => 'CCO', 'name' => 'Client One', 'email' => 'client4.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'invoice_prefix' => 'CCT', 'name' => 'Client Two', 'email' => 'client4.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'invoice_prefix' => 'CCO', 'name' => 'Client One', 'email' => 'client5.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'invoice_prefix' => 'CCT', 'name' => 'Client Two', 'email' => 'client5.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
            ];
            Clients::truncate();
            Clients::insert($Clients);
            print_r(PHP_EOL.'Test clients are generated.'.PHP_EOL);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
