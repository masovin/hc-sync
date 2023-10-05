<?php

namespace HcSync\Commands;

use HcSync\HumanCapital;
use Illuminate\Console\Command;

class HcSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Human Capital Syncronize';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sync Start');
        $hc = new HumanCapital();
        $hc->sync();
    }
}
