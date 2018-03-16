<?php namespace RafMuseum\CasualtyForms\Models;

use FilesystemIterator;
use DB;
use Model;
use Cache;
use Carbon\Carbon;
use RainLab\User\Components\Session;

/**
 * Model
 */
class CasualtyForm extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Nullable;

    /*
     * Validation
     */
    public $rules = [];

    /**
     * @var array The attributes that are mass assignable (in the front end).
     */
    protected $fillable = [
        'id',
        'rank',
        'first_names',
        'surname',
        'birth_date',
        'regiment_corps',
        'report_date_first',
        'report_date_last',
        'death_date',
        'medical_information',
        'started_by_id',
        'completed_by_id',
        'approved_by_id',
        'child_form',
        'parent_form_id',
        'flagged',
        'flagged_notes'
    ];

    /**
     * @var array The attributes that are able to be saved as null.
     */
    protected $nullable = [
        'birth_date',
        'death_date'
    ];

    /**
     * @var array The attributes that can be stored as a JSON string.
     */
    protected $jsonable = ['rank'];

    /**
     * @var array The date columns, this is different to October's $dates.
     */
    protected $dateFields = [
        'birth_date',
        'death_date',
    ];

    /**
     * @var array The optional states for transcription inputs.
     */
    protected $fieldSpecialStates = [
        'illegible' => array(
            'label' => 'Illegible',
            'value' => '?',
            'datevalue' => '1 January 0001',
            'datevalraw' => '00001-01-01',
        ),
        'nodata' => array(
            'label' => 'Blank',
            'value' => 'N/A',
            'datevalue' => '2 January 0001',
            'datevalraw' => '00001-01-02',
        ),
    ];

    /*
     * Relationships
     * These assume the foreign key is the relationship name affixed by `_id`.
     * For example, the `started_by` relationship is referenced by the
     * `started_by_id` column in the table.
     */
    public $belongsTo = [
        'started_by' => 'RainLab\User\Models\User',
        'completed_by' => 'RainLab\User\Models\User',
        'approved_by' => 'RainLab\User\Models\User',
        'parent_form' => 'RafMuseum\CasualtyForms\Models\CasualtyForm',
    ];

    public $hasMany = [
        'child_forms' => ['RafMuseum\CasualtyForms\Models\CasualtyForm', 'key' => 'parent_form_id']
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rafmuseum_casualtyforms_forms';

    /**
     * Returns the `fieldSpecialStates` model.
     */
    public function getFieldSpecialStates() {
        return $this->fieldSpecialStates;
    }

    /**
     * Converts front end date fields to format of choice.
     * @var string The date format required.
     */
    public function convertDates($format) {
        // Loop through the date fields.
        foreach ($this->dateFields as $dateField) {
            if ($this[$dateField]) {
                // Convert if it exists in the current model.
                $date = strtotime($this[$dateField]);
                $this[$dateField] = date($format, $date);
            }
        }
    }

    /**
     * Strips whitespace and common punctuation from first names,
     * @var string The string to convert.
     */
    protected function normaliseFirstName($string) {
        return trim(str_replace('  ', ' ', str_replace(['.', ','], ' ', $string)));
    }

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
     * Scope query to find "abandoned" forms; Any form that has been
     * started and not finished for a couplea' days.
     */
    public function scopeAbandoned($query)
    {
        return $query->whereNotNull('started_by_id')
                     ->whereNull('completed_by_id')
                     ->where('started_at', '<', Carbon::now()->subHour());
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
     * Scope a query to only include forms marked as child forms.
     */
    public function scopeChildren($query)
    {
        return $query->where('child_form', true)
                     ->whereNull('parent_form_id');
    }

    /**
     * Scope a query to only include forms marked with special states.
     */
    public function scopeSpecialState($query, $state)
    {
        $illegibleState = $this->fieldSpecialStates[$state];

        return $query->where('rank', $illegibleState['value'])
                     ->orWhere('first_names', $illegibleState['value'])
                     ->orWhere('surname', $illegibleState['value'])
                     ->orWhere('regiment_corps', $illegibleState['value'])
                     ->orWhere('birth_date', $illegibleState['datevalraw'])
                     ->orWhere('death_date', $illegibleState['datevalraw']);
    }

    /**
     * Scope a query to only include user flagged forms.
     */
    public function scopeFlagged($query)
    {
        return $query->where('flagged', true);
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
        // All results have to be approved and not child forms.
        $query->approved()->where('child_form', false);

        // WHERE `approved_by_id` NOT NULL AND (x OR y OR ...)
        $query->where(function($query)use($tags) {
            // Go through each of the fields and search.
            foreach($tags as $field => $tag) {
                // Date specific.
                if (strstr($field, 'date') && $tag)
                    $tag = date('Y-m-d', strtotime($tag));

                // First name specific.
                // if ($field === 'first_names')
                //     $tag = $this->normaliseFirstName($tag);
                
                // Basic search.
                if ($tag) $query->where($field, 'LIKE', "%$tag%");
            }
        });

        return $query;
    }

    /**
     * Counts the number of forms completed by a certain user.
     * @param array $userId The user ID to query.
     */
    public static function completedByUser($userId) {
        $casualtyForms = new static;
        $count = $casualtyForms->where('completed_by_id', $userId)->count();

        // Return just the count number.
        return $count;
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
