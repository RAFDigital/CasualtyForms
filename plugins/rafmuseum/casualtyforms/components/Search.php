<?php namespace RafMuseum\CasualtyForms\Components;

use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;

class Search extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Search',
            'description' => 'Provides the search form for the Casualty Forms.'
        ];
    }

    /**
     * Submit the search form.
     */
    public function onRun()
    {
        // Get the search tags.
        $tags = get();

        // Remove the page var before searching.
        unset($tags['page']);

        // Get the search term.
        $results = CasualtyForm::search($tags)->paginate(10);

        // Build the pagination links with the existing get vars.
        $pagination = $results->appends($tags)->render();

        // Add front end vars.
        $this->page['pagination'] = $pagination;
        $this->page['results'] = $results;
    }
}
