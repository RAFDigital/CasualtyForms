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
        // We need to get the current user out of the session.
        $session = new Session();
        $user = $session->user();
        // Get the completed forms not completed by the current user.
        return $query->where('completed_by_id', '!=', $user->id);
    }

    /**
     * Get the total records for each.
     */
     public function getTotals($query)
     {
         $totals = array(
             'toTranscribe' => $query->whereNull('completed_by_id'),
            'toApprove' => $query->where('completed_by_id', '!=', $user->id)
        );

        return $totals;
     }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rafmuseum_casualtyforms_forms';
}
