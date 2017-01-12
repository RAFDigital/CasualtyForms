<?php namespace RafMuseum\CasualtyForms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class FormsIllegible extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RafMuseum.CasualtyForms', 'main-menu-item', 'side-menu-item2');
    }

    public function listExtendQuery($query)
    {
        // We just want forms with illegible fields here.
        $query->where('rank', '?')
              ->orWhere('first_names', '?')
              ->orWhere('surname', '?')
              ->orWhere('regiment_corps', '?')
              ->orWhere('report_date_first', '0001-01-01')
              ->orWhere('report_date_last', '0001-01-01')
              ->orWhere('death_date', '0001-01-01')
              ->get();
    }
}
