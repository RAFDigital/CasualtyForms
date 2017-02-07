<?php namespace RafMuseum\CasualtyForms\Components;

use Cms\Classes\ComponentBase;

class Search extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Search Component',
            'description' => 'Provides the search form for the Casualty Forms.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
}
