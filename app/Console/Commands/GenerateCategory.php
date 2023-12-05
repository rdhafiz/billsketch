<?php

namespace App\Console\Commands;

use App\Models\Categories;
use Illuminate\Console\Command;

class GenerateCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-category';

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
            $Categories = [
                ['user_id' => '1', 'name' => 'Cost', 'color' => '#d1ca00'],
                ['user_id' => '1', 'name' => 'Income', 'color' => '#149f1d'],
                ['user_id' => '2', 'name' => 'Cost', 'color' => '#d1ca00'],
                ['user_id' => '2', 'name' => 'Income', 'color' => '#149f1d'],
                ['user_id' => '3', 'name' => 'Cost', 'color' => '#d1ca00'],
                ['user_id' => '3', 'name' => 'Income', 'color' => '#149f1d'],
                ['user_id' => '4', 'name' => 'Cost', 'color' => '#d1ca00'],
                ['user_id' => '4', 'name' => 'Income', 'color' => '#149f1d'],
                ['user_id' => '5', 'name' => 'Cost', 'color' => '#d1ca00'],
                ['user_id' => '5', 'name' => 'Income', 'color' => '#149f1d'],
            ];
            Categories::insert($Categories);
            print_r(PHP_EOL.'Test Categories are generated.'.PHP_EOL);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
