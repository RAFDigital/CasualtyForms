<?php namespace RafMuseum\CasualtyForms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class FormsIllegible extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = ['rafmuseum.casualtyforms.formsillegible'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RafMuseum.CasualtyForms', 'casualtyforms', 'illegibleforms');
    }

    public function listExtendQuery($query)
    {
        // We just want forms with illegible fields here.
        $query->specialState('illegible')->get();
    }
}
