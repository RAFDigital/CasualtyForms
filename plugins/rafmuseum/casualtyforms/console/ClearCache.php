<?php

namespace RafMuseum\CasualtyForms\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearCache extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'rafmuseum:clear-cache';

    /**
     * @var string The console command description.
     */
    protected $description = 'Clears the CMS and framework cache.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $file = new Filesystem;

        $file->cleanDirectory('storage/framework/cache');
        $file->cleanDirectory('storage/cms/cache');

        $this->info('Framework and CMS cache cleared!');        
    }
}
