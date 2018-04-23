<?php namespace RafMuseum\CasualtyForms;

use System\Classes\PluginBase;
use View;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'RafMuseum\CasualtyForms\Components\TranscriptionForm' => 'transcriptionForm',
            'RafMuseum\CasualtyForms\Components\Search' => 'search',
            'RafMuseum\CasualtyForms\Components\FlagRecord' => 'flagRecord'
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

    public function registerFormWidgets()
    {
        return [
            'RafMuseum\CasualtyForms\FormWidgets\ParentForm' => [
                'label' => 'Parent Form',
                'code' => 'parentform'
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'rafmuseum.casualtyforms.forms' => [
                'label' => 'Access all forms',
                'tab' => 'Casualty Forms',
                'roles' => ['developer', 'owner', 'museum-staff']
            ],
            'rafmuseum.casualtyforms.formsapproved' => [
                'label' => 'Access approved forms',
                'tab' => 'Casualty Forms',
                'roles' => ['developer', 'owner', 'museum-staff']
            ],
            'rafmuseum.casualtyforms.formschildren' => [
                'label' => 'Access child forms',
                'tab' => 'Casualty Forms',
                'roles' => ['developer', 'owner', 'museum-staff']
            ],
            'rafmuseum.casualtyforms.formsflagged' => [
                'label' => 'Access flagged forms',
                'tab' => 'Casualty Forms',
                'roles' => ['developer', 'owner', 'museum-staff']
            ],
            'rafmuseum.casualtyforms.formsillegible' => [
                'label' => 'Access illegible forms',
                'tab' => 'Casualty Forms',
                'roles' => ['developer', 'owner', 'museum-staff']
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
