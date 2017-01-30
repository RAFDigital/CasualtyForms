<?php namespace RafMuseum\CasualtyForms\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

class Instructions extends ReportWidgetBase
{
    public function render()
    {
        return $this->makePartial('widget');
    }
}
