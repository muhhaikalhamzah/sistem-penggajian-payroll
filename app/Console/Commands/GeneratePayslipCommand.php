<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class GeneratePayslipCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-payslip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger GeneratePayslipJob for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching GeneratePayslipJob...');
        dispatch(new \App\Jobs\GeneratePayslipJob());
        $this->info('Job dispatched successfully.');
    }
}
