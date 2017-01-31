<?php namespace RafMuseum\Volunteers\Components;

use FilesystemIterator;
use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;

class Dashboard extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Dashboard Component',
            'description' => 'Overview of volunteer progress.'
        ];
    }

    public function onRun()
    {
        // Get the user.
        $user = $this->page['user'];

        // Total progress.
        $progress = array('approved' => 0, 'completed' => 0, 'total' => 0);

        // Iterate through all the files. This *may* be slow...
        $fi = new FilesystemIterator(
            base_path() . config('cms.storage.media.path') .
            config('casualtyforms.imagefile.dir'),
            FilesystemIterator::SKIP_DOTS
        );

        $progress['total'] = iterator_count($fi);
        $progress['completed'] = CasualtyForm::completed()->count();
        $progress['approved'] = CasualtyForm::approved()->count();

        $this->page['progress'] = $progress;

        // Leaderboard.
    }
}
