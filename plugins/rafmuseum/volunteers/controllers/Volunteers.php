<?php namespace RafMuseum\Volunteers\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Volunteers extends Controller
{
    public $implement = [
        'Backend\Behaviors\ImportExportController'
    ];

    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.User', 'user', 'export');
    }
}
