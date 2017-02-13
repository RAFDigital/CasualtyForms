<?php namespace RafMuseum\CasualtyForms\Models;

use FilesystemIterator;
use DB;
use Model;
use Cache;
use RainLab\User\Components\Session;

/**
 * Model
 */
class CasualtyForm extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'rank',
        'first_names',
        'surname',
        'regiment_corps',
        'report_date_first',
        'report_date_last',
        'death_date',
        'medical_information',
        'started_by_id',
        'completed_by_id',
        'approved_by_id'
    ];

    protected $jsonable = ['rank'];

    /*
     * Relationships
     */
    public $belongsTo = [
        // `started_by_id` in table.
        'started_by' => ['RainLab\User\Models\User', 'table' => 'users'],
        // `completed_by_id` in table.
        'completed_by' => ['RainLab\User\Models\User', 'table' => 'users'],
        // `approved_by_id` in table.
        'approved_by' => ['RainLab\User\Models\User', 'table' => 'users']
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rafmuseum_casualtyforms_forms';

    /**
     * Scope a query to only include forms that have been created but not complete.
     */
    public function scopeToFinish($query)
    {
        // We need to get the current user out of the session.
        $session = new Session();
        $user = $session->user();
        // Get the forms that have been started but not finished.
        return $query->where('started_by_id', $user->id)
                     ->whereNull('completed_by_id');
    }

    /**
     * Scope a query to only include completed forms.
     */
    public function scopeToApprove($query)
    {
        // We need to get the current user out of the session.
        $session = new Session();
        $user = $session->user();
        // Get the completed forms not completed by the current user and not completed.
        return $query->where('completed_by_id', '!=', $user->id)
                     ->whereNull('approved_by_id');
    }

    /**
     * Scope a query to only include completed forms.
     */
    public function scopeCompleted($query)
    {
        // Not approved but completed.
        return $query->whereNotNull('completed_by_id');
    }

    /**
     * Scope a query to only include approved forms.
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by_id');
    }

    /**
     * Scope a query to only include approved forms.
     */
    public function scopeApprovedByCompletor($query)
    {
        return $query->with(['completed_by' => function($query) {
                        $query->addSelect(['id', 'name']);
                     }])
                     ->select(
                         'completed_by_id',
                         DB::raw('count(*) as `total`'))
                     ->whereNotNull('approved_by_id')
                     ->groupBy('completed_by_id');
    }

    /**
     * Scope for searching various fields.
     * @param  array $tags The search field array.
     */
    public function scopeSearch($query, $tags)
    {
        // All results have to be approved.
        $query->whereNotNull('approved_by_id');

        // WHERE `approved_by_id` NOT NULL AND (x OR y OR ...)
        $query->where(function($query)use($tags) {
            $firstField = true; // First flag.

            // Go through each of the fields and search.
            foreach($tags as $field => $tag) {
                if($firstField) {
                    // The first field query needs to be a plain where.
                    $query->where($field, 'LIKE', "%$tag%");

                    $firstField = false;
                } else {
                    $query->orWhere($field, 'LIKE', "%$tag%");
                }

            }
        });

        return $query;
    }

    /**
     * Scope a query to only include approved forms.
     * @param  bool $flush To flush the cache or not.
     */
    public static function countFiles($flush = false)
    {
        // Flush the cache if requested.
        if ($flush) Cache::flush();

        // Cache the file count for performance.
        $fileCount = Cache::rememberForever('fileCount', function() {
            // Iterate through all the files. This *may* be slow...
            $fi = new FilesystemIterator(
                base_path() . config('cms.storage.media.path') .
                config('casualtyforms.imagefile.dir'),
                FilesystemIterator::SKIP_DOTS
            );

            // Count.
            return iterator_count($fi);
        });

        // Return the file count.
        return $fileCount;
    }
}
