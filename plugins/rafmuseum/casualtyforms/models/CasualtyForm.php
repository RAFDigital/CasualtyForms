<?php namespace RafMuseum\CasualtyForms\Models;

use Model;
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
        'completed_by_id',
        'approved_by_id'
    ];

    /*
     * Relationships
     */
    public $belongsTo = [
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
        // We need to get the current user out of the session.
        $session = new Session();
        $user = $session->user();
        // Get the completed forms not completed by the current user and not completed.
        return $query->where('completed_by_id', '!=', $user->id)
                     ->whereNull('approved_by_id');
    }

    /**
     * Scope a query to only include approved forms.
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by_id');
    }

    /**
     * Get the total records for each.
     */
     public function getTotals($query)
     {
         $totals = array(
            'toTranscribe' => $query->whereNull('completed_by_id'),
            'toApprove' => $query->where('completed_by_id', '!=', $user->id)
                                 ->whereNull('approved_by_id')
        );

        return $totals;
     }
}
