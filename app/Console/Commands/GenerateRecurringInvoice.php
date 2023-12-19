<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Recurring\Recurring;
use Illuminate\Console\Command;

class GenerateRecurringInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recurring-invoice';

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
        Recurring::run();
    }
}
