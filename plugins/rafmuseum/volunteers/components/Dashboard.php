<?php namespace RafMuseum\Volunteers\Components;

use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;
use RafMuseum\UserTimelogs\Models\UserTimelog;

class Dashboard extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Dashboard Component',
            'description' => 'Overview of volunteer progress.'
        ];
    }

    public function onRun()
    {
        // Total progress.
        $this->page['progress'] = $this->getProgress();

        // Forms approved leaderboard.
        $this->page['leadApprovals'] = CasualtyForm::approvedByCompletor()
                                       ->orderBy('total', 'DESC')
                                       ->limit(10)->get();

        // Hours logged leaderboard.
        $this->page['leadHours'] = UserTimelog::logTotals()
                                   ->orderBy('time_logged', 'DESC')
                                   ->limit(10)->get();
    }

    /**
     * Get the overview totals for transcriptions.
     */
    protected function getProgress()
    {
        $progress = array('approved' => 0, 'completed' => 0, 'total' => 0);

        // Get all the progress attributes.
        $progress['total'] = CasualtyForm::countFiles();
        $progress['completed'] = CasualtyForm::completed()->count();
        $progress['approved'] = CasualtyForm::approved()->count();

        return $progress;
    }
}
