<?php namespace RafMuseum\CasualtyForms\Models;

use Model;

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

    /*
     * Relationships
     */
    public $belongsTo = [
        'completed_by' => ['RainLab\User\Models\User', 'table' => 'users'],
        'approved_by' => ['RainLab\User\Models\User', 'table' => 'users']
    ];

    /**
     * Scope a query to only include forms that aren't complete.
     */
    public function scopeToTranscribe($query)
    {
        return $query->whereNull('completed_by_id');
    }

    /**
     * Scope a query to only include completed forms.
     */
    public function scopeToApprove($query)
    {
        return $query->whereNotNull('completed_by_id');
    }

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rafmuseum_casualtyforms_forms';
}
