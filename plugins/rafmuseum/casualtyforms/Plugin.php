<?php namespace RafMuseum\CasualtyForms;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function registerMailTemplates()
    {
        return [
            'rafmuseum.casualtyforms::mail.message' => 'Test message email.'
        ];
    }

    public function registerSchedule($schedule)
    {
        // Do nothing for now.
        //$schedule->command('rafmuseum:mycommand')->everyMinute();
    }

    public function register()
    {
        $this->registerConsoleCommand('rafmuseum.mycommand', 'RafMuseum\CasualtyForms\Console\MyCommand');
    }
}
