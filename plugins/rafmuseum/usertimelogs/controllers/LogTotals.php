<?php namespace RafMuseum\UserTimelogs\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class LogTotals extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\ReorderController',
        'Backend\Behaviors\ImportExportController'
    ];

    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RafMuseum.UserTimelogs', 'main-menu-item');
    }

    public function listExtendQuery($query)
    {
        $query->logTotals()->get();
    }
}
