<?php namespace RafMuseum\CasualtyForms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class FormsApproved extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        'Backend\Behaviors\ImportExportController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RafMuseum.CasualtyForms', 'main-menu-item', 'side-menu-item3');
    }

    public function listExtendQuery($query)
    {
        // We just want forms with illegible fields here.
        $query->whereNotNull('approved_by_id')->get();
    }
}
