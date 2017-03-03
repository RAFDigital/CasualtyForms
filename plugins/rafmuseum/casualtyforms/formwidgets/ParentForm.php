<?php namespace RafMuseum\CasualtyForms\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * ParentForm Form Widget
 */
class ParentForm extends FormWidgetBase
{
    /*
     * Config attributes
    //  */
    protected $modelClass = '\RafMuseum\CasualtyForms\Models\CasualtyForm';
    protected $selectFrom = 'id';
    protected $pattern = 'radio';

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'rafmuseum_casualtyforms_parent_form';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->fillFromConfig([
            'modelClass',
            'selectFrom',
            'pattern'
        ]);

        $this->assertModelClass();

        parent::init();
    }

    /**
     * Gets the two neighbouring records as potential parent options.
     */
    public function getOptions()
    {
        // Previous record.
        $options[] = $this->model->newQuery()
                     ->where($this->selectFrom, '<', $this->model->id)
                     ->orderby('id','desc')->first();
        // Next record.
        $options[] = $this->model->newQuery()
                     ->where($this->selectFrom, '>', $this->model->id)
                     ->orderby('id','asc')->first();

        return $options;
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('parentform');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['inputType'] = $this->pattern;
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
        $this->vars['childForm'] = $this->model->child_form;
        $this->vars['options'] = $this->getOptions();
    }

    /**
     * Gets the model class fron the list config.
     */
    protected function assertModelClass()
    {
        if( !isset($this->modelClass) || !class_exists($this->modelClass) ) {
            throw new \InvalidArgumentException(
                sprintf("Model class {%s} not found.", $this->modelClass)
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('css/parentform.css', 'RafMuseum.CasualtyForms');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
