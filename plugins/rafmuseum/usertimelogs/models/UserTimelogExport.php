<?php namespace RafMuseum\UserTimelogs\Models;

use Backend\Models\ExportModel;
use DB;

/**
 * Model
 */
 class UserTimelogExport extends ExportModel
{
    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'log_totals',
        'from_date',
        'to_date'
    ];

    /**
     * Export the data.
     */
    public function exportData($columns, $sessionKey = null)
    {
        $logs = new UserTimelog;

        // Let's include the related columns.
        $logs = $logs->with(['user'])->select('*');

        // From date and to date.
        if ($this->from_date && $this->to_date) {
            $logs = $logs->whereBetween('signin_time', [
                $this->from_date,
                $this->to_date
            ]);
        }

        // Do a different query based on options.
        if ($this->log_totals) $logs = $logs->logTotals();
        else $logs->addSelect(DB::raw('TIMEDIFF(signout_time, signin_time) as `time_logged`'));

        // Ok send the query.
        $logs = $logs->get();

        $logs->each(function($log) use ($columns) {
            $log->addVisible($columns);
        });

        // Here we convert the relations into json for export.
        $collection = collect($logs->toArray());

        $data = $collection->map(function ($item) {
            if (is_array($item)){
                foreach($item as $key => $value) {
                    if (is_array($value)) {
                        // Only display the name.
                        $item[$key] = $value['name'];
                    }
                }
            }
            return $item;
        });


        return $data->toArray();
    }
}
