<?php namespace RafMuseum\CasualtyForms\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MyCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'rafmuseum:mycommand';

    /**
     * @var string The console command description.
     */
    protected $description = 'Does something cool.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        trace_log('rafmuseum:mycommand executing!');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
