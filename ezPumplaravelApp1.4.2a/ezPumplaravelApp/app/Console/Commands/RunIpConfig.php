<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunIpConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:ipconfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run ipconfig command and display output';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Windows ke liye ipconfig
        $output = shell_exec('ipconfig');

        // Output console pe dikhaye
        $this->info($output);
    }
}
