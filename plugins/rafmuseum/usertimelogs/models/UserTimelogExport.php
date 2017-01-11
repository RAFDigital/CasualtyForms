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

        // Let's include the related columns.
        $logs = $logs->with([
            'user' => function($query){ $query->addSelect(['id', 'name']); }
        ]);

        // Do a different query based on options.
        // if($this->approved_forms_only) {
        //     $logs = $logs->whereNotNull('approved_by_id')->get();
        // } else {
            $logs = $logs->get();
        //}

        $logs->each(function($log) use ($columns) {
            $log->addVisible($columns);
        });

        // Here we convert the relations into json for export.
        $collection = collect($logs->toArray());

        $data = $collection->map(function ($item) {
            if(is_array($item)){
                foreach($item as $key => $value) {
                    if(is_array($value)) {
                        $item[$key] = json_encode($value);
                    }
                }
            }
            return $item;
        });

        return $data->toArray();
    }
}
