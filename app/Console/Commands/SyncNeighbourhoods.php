<?php

namespace Depotwarehouse\Neighbourhoods\Console\Commands;

use Illuminate\Console\Command;

class SyncNeighbourhoods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neighbourhoods:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all the neighbourhoods with their socrata source';
    /**
     * @var \Depotwarehouse\Neighbourhoods\Jobs\SyncNeighbourhoods
     */
    private $internalCommand;

    public function __construct(\Depotwarehouse\Neighbourhoods\Jobs\SyncNeighbourhoods $internalCommand)
    {
        parent::__construct();
        $this->internalCommand = $internalCommand;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->internalCommand->syncNeighbourhoods();

        $this->output->writeln("Synced all neighbourhoods!");
    }
}
