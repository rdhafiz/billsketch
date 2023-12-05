<?php

namespace App\Console\Commands;

use App\Models\Employees;
use Illuminate\Console\Command;

class GenerateEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-employees';

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
            $Employees = [
                ['user_id' => '1', 'invoice_prefix' => 'ECO',  'name' => 'Employee One', 'email' => 'employee.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '1', 'invoice_prefix' => 'ECT',  'name' => 'Employee Two', 'email' => 'employee.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'invoice_prefix' => 'ECO',  'name' => 'Employee One', 'email' => 'employee.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '2', 'invoice_prefix' => 'ECT',  'name' => 'Employee Two', 'email' => 'employee.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'invoice_prefix' => 'ECO',  'name' => 'Employee One', 'email' => 'employee.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '3', 'invoice_prefix' => 'ECT',  'name' => 'Employee Two', 'email' => 'employee.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'invoice_prefix' => 'ECO',  'name' => 'Employee One', 'email' => 'employee.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '4', 'invoice_prefix' => 'ECT',  'name' => 'Employee Two', 'email' => 'employee.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'invoice_prefix' => 'ECO',  'name' => 'Employee One', 'email' => 'employee.one@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
                ['user_id' => '5', 'invoice_prefix' => 'ECT',  'name' => 'Employee Two', 'email' => 'employee.two@gmail.com', 'phone' => '12345678', 'address' => '6th Floor, Shahi Tower, Sector 7, Plot k/25', 'city' => 'Jashore', 'country' => 'Bangladesh'],
            ];
            Employees::insert($Employees);
            print_r(PHP_EOL.'Test employees are generated.'.PHP_EOL);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
