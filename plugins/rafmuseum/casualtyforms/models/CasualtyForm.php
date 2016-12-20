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
    //  public $belongsTo = [
    //     'user' => ['RainLab\User\Models\User', 'key' => 'completed_by']
    // ];

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
