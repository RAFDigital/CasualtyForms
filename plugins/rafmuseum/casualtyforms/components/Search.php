<?php namespace RafMuseum\CasualtyForms\Components;

use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

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
        // Get the search tags and remove empties.
        $tags = array_filter(get());

        // Remove the page var before searching.
        unset($tags['page']);

        $perPage = 10;
        $page = get('page') ?: 1;

        // Carry out a couple of searches.
        $default = CasualtyForm::search($tags)->get();
        $soft = CasualtyForm::searchSoft($tags)->get();

        // Merge the two queries together.
        $results = $default->merge($soft);
        // $results = $soft;

        // Get the number of results.
        $count = $results->count();

        // Mad pagination for the combined collections.
        $pagination = new Paginator($results, $count, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'query' => get()
        ]);

        $this->page['count'] = $count;
        $this->page['pagination'] = $pagination;
        $this->page['results'] = $results->forPage($page, $perPage);
    }
}
