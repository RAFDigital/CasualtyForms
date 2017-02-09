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

    /**
     * Submit the search form.
     */
    public function onSubmit()
    {
        // Get the search term.
        $term = post('term');

        trace_log($term);
    }
}
