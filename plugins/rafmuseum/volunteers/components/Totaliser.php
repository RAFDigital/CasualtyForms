<?php namespace RafMuseum\Volunteers\Components;

use DB;
use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use RainLab\User\Models\User;
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
        // Add necessary files for this page.
        $this->loadAssets();

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

        // Get no of volunteers.
        $this->page['volunteers'] = User::all()->count();

        // Get weekly data (not working don't know why).
        $this->page['weekStats'] = $this->getWeekStats();

        // Get some graph data.
        $this->page['data'] = $this->getSampleGraphData();
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

    /**
     * A few more wee stats for the page.
     */
    protected function getWeekStats()
    {
        $weekStats = array('completed' => array(), 'approved' => array());
        $thisWeek = Carbon::now()->subWeek();
        $lastWeek = array($thisWeek, Carbon::now()->subWeeks(2));

        // Get all the weekly stats.
        $weekStats['completed']['this'] = CasualtyForm::completed()
            ->where('completed_at', '>', $thisWeek)->count();
        $weekStats['completed']['last'] = CasualtyForm::completed()
            ->whereBetween('completed_at', $lastWeek)->count();

        $weekStats['approved']['this'] = CasualtyForm::approved()
            ->where('approved_at', '>',  $thisWeek)->count();
        $weekStats['approved']['last'] = CasualtyForm::approved()
            ->whereBetween('approved_at', $lastWeek)->count();

        // Get the change.
        if($weekStats['completed']['this'] > $weekStats['completed']['last']) {
            $weekStats['completed']['change'] = 'positive';
        } elseif($weekStats['completed']['this'] < $weekStats['completed']['last']) {
            $weekStats['completed']['change'] = 'negative';
        }

        if($weekStats['approved']['this'] > $weekStats['approved']['last']) {
            $weekStats['approved']['change'] = 'positive';
        } elseif($weekStats['approved']['this'] < $weekStats['approved']['last']) {
            $weekStats['approved']['change'] = 'negative';
        }

        return $weekStats;
    }

    /**
     * Returns fake, but correctly formatted, data for the line graph.
     */
    protected function getSampleGraphData()
    {
        return array(
            [1477857082000, 400], [1477943482000, 380], [1478029882000, 340],
            [1478116282000, 540], [1478202682000, 440], [1478289082000, 360],
            [1478740172000, 220]
        );
    }

    /**
     * Adds the css and js files required for the transcription form.
     */
    protected function loadAssets()
    {
        // This is how you include backend form styles/js.
        $this->addJs('/modules/system/assets/ui/storm-min.js', 'core');
    }
}
