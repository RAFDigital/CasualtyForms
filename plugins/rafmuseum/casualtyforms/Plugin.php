<?php namespace RafMuseum\CasualtyForms;

use System\Classes\PluginBase;
use View;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'RafMuseum\CasualtyForms\Components\TranscriptionForm' => 'transcriptionform',
            'RafMuseum\CasualtyForms\Components\Search' => 'search'
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMailTemplates()
    {
        return [
            'rafmuseum.casualtyforms::mail.illegible-forms' => 'Illegible forms notification email.'
        ];
    }

    public function registerSchedule($schedule)
    {
        // Do nothing for now.
        //$schedule->command('rafmuseum:mycommand')->everyMinute();
    }

    public function registerReportWidgets()
    {
        return [
            'RafMuseum\CasualtyForms\ReportWidgets\Instructions' => [
                'label'   => 'Instructions',
                'context' => 'dashboard'
            ]
        ];
    }

    public function register()
    {
        // Register global vars for email template.
        View::share('site_url', url());

        // Register a console command.
        $this->registerConsoleCommand('rafmuseum.mycommand', 'RafMuseum\CasualtyForms\Console\MyCommand');
    }
}
