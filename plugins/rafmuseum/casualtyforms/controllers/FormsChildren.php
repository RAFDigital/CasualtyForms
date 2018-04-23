<?php namespace RafMuseum\CasualtyForms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class FormsChildren extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = ['rafmuseum.casualtyforms.formschildren'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RafMuseum.CasualtyForms', 'casualtyforms', 'childforms');
    }

    public function listExtendQuery($query)
    {
        // We just want forms that are children
        $query->children()->get();
    }
}
