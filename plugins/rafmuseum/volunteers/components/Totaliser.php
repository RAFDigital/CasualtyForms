<?php namespace RafMuseum\Volunteers\Components;

use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;
use RafMuseum\UserTimelogs\Models\UserTimelog;

class Totaliser extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Totaliser Component',
            'description' => 'Overview of volunteer progress.'
        ];
    }

    public function onRun()
    {
        // Total progress, send in a `flushcache` get var if present.
        $this->page['progress'] = $this->getProgress(get('flushcache'));

        // Forms approved leaderboard (top 10).
        $this->page['leadApprovals'] = CasualtyForm::approvedByCompletor()
                                       ->orderBy('total', 'DESC')
                                       ->limit(10)->get();

        // Hours logged leaderboard (top 10).
        $this->page['leadHours'] = UserTimelog::logTotals()
                                   ->orderBy('time_logged', 'DESC')
                                   ->limit(10)->get();
    }

    /**
     * Get the overview totals for transcriptions.
     */
    protected function getProgress($flushCache = false)
    {
        $progress = array('approved' => 0, 'completed' => 0, 'total' => 0);

        // Get all the progress attributes.
        $progress['total'] = CasualtyForm::countFiles($flushCache);
        $progress['completed'] = CasualtyForm::completed()->count();
        $progress['approved'] = CasualtyForm::approved()->count();

        return $progress;
    }
}
