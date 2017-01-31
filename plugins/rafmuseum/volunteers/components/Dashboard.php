<?php namespace RafMuseum\Volunteers\Components;

use FilesystemIterator;
use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;

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
        $this->page['progress'] = $this->getTotals();

        // Forms approved leaderboard.
        $this->page['leadApprovals'] = CasualtyForm::approvedByCompletor()
                                   ->orderBy('total', 'DESC')
                                   ->limit(10)->get();
    }

    /**
     * Get the overview totals for transcriptions.
     */
    protected function getTotals()
    {
        $progress = array('approved' => 0, 'completed' => 0, 'total' => 0);

        // Iterate through all the files. This *may* be slow...
        $fi = new FilesystemIterator(
            base_path() . config('cms.storage.media.path') .
            config('casualtyforms.imagefile.dir'),
            FilesystemIterator::SKIP_DOTS
        );

        // Add 'em all in.
        $progress['total'] = iterator_count($fi);
        $progress['completed'] = CasualtyForm::completed()->count();
        $progress['approved'] = CasualtyForm::approved()->count();

        return $progress;
    }
}
