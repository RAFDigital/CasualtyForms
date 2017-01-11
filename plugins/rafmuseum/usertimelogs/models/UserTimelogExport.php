<?php namespace RafMuseum\UserTimelogs\Models;

use Backend\Models\ExportModel;

/**
 * Model
 */
 class UserTimelogExport extends ExportModel
{
    /**
     * @var array Fillable fields
     */
    protected $fillable = ['approved_forms_only'];

    /**
     * Export the data.
     */
    public function exportData($columns, $sessionKey = null)
    {
        $logs = new UserTimelog;

        // Do a different query based on options.
        // if($this->approved_forms_only) {
        //     $logs = $logs->whereNotNull('approved_by_id')->get();
        // } else {
            $logs = $logs->all();
        //}

        $logs->each(function($log) use ($columns) {
            $log->addVisible($columns);
        });

        return $logs->toArray();
    }
}
