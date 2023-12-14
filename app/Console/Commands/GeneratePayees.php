<?php

namespace App\Console\Commands;

use App\Models\Payees;
use Illuminate\Console\Command;

class GeneratePayees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-payees';

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
            $Payees = [
                ['user_id' => '1', 'invoice_prefix' => 'ECO',  'name' => 'Payee One', 'email' => 'payee1.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '1', 'invoice_prefix' => 'ECT',  'name' => 'Payee Two', 'email' => 'payee1.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'invoice_prefix' => 'ECO',  'name' => 'Payee One', 'email' => 'payee2.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'invoice_prefix' => 'ECT',  'name' => 'Payee Two', 'email' => 'payee2.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'invoice_prefix' => 'ECO',  'name' => 'Payee One', 'email' => 'payee3.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'invoice_prefix' => 'ECT',  'name' => 'Payee Two', 'email' => 'payee3.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'invoice_prefix' => 'ECO',  'name' => 'Payee One', 'email' => 'payee4.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'invoice_prefix' => 'ECT',  'name' => 'Payee Two', 'email' => 'payee4.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'invoice_prefix' => 'ECO',  'name' => 'Payee One', 'email' => 'payee5.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'invoice_prefix' => 'ECT',  'name' => 'Payee Two', 'email' => 'payee5.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
            ];
            Payees::truncate();
            Payees::insert($Payees);
            print_r(PHP_EOL.'Test payees are generated.'.PHP_EOL);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
