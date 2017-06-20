/**
 * TranscriptionForm.js
 * All the scripts for the transcription form component.
 */
var TranscriptionForm = (function(exports) {
    'use strict';

    /* Consts */
    var TOGGLE_FIELD_PROPS_CLASS = '.toggle-field-props',
        FIELD_PROPS_RESET_CLASS = '.field-props-reset',
        TOGGLE_DATEPICKER_MODE_CLASS = '.toggle-datepicker-mode',
        TRANSCRIBE_STAGE_DATA_FIELD = '[name="completed_by_id"]',
        APPROVAL_STAGE_DATA_FIELD = '[name="approved_by_id"]',
        FORM_TAG = 'form',
        INPUT_SELECTOR = 'input',
        REQUIRED_INPUT_SELCTOR = INPUT_SELECTOR + '[required]',
        SUBMIT_BUTTON = '[type="submit"]',
        INPUT_GROUP_CLASS = '.input-group',
        FORM_CONTROL_CLASS = '.form-control',
        DROPDOWN_TOGGLE_CLASS = '.dropdown-toggle',
        CHILD_TOGGLE_SECTIONS = '#childHidden, #childShow',
        RETARD_IMAGE_ZOOM_SELECTOR = 'img.image-zoom-retard',
        SURVEY_TRIGGER = '[data-target="#survey-modal"]',
    /* Vars */
        backToApproval = false,
        imageZoomsInitialised = false,
        fieldSpecialStates = [];

    /**
     * Function to toggle the illegible action.
     * @param {object} event The click event.
     */
    function toggleFieldProps(event) {
        var $inputGroup = $(this).parents(INPUT_GROUP_CLASS);

        // Change the look of the dropdown.
        $inputGroup.find(DROPDOWN_TOGGLE_CLASS + ' span').removeClass('caret')
            .text(this.dataset.label ? this.dataset.label : this.dataset.value);

        // Change the value of the input and trigger the change.
        $inputGroup.find(FORM_CONTROL_CLASS)
            .prop(this.dataset).trigger('change');

        // Hide/show the right buttons.
        $inputGroup.find(TOGGLE_FIELD_PROPS_CLASS).hide();
        $inputGroup.find(FIELD_PROPS_RESET_CLASS).show();

        event.preventDefault();
    }

    /**
     * Function to reset the field props.
     * @param {object} event The click event.
     */
    function fieldPropsReset(event) {
        var $inputGroup = $(this).parents(INPUT_GROUP_CLASS);

        // Change the look of the dropdown.
        $inputGroup.find(DROPDOWN_TOGGLE_CLASS + ' span')
            .addClass('caret').text('');

        // Change the value of the input and trigger the change.
        $inputGroup.find(FORM_CONTROL_CLASS)
            .prop({readonly: false, value: ''}).trigger('change');

        // Hide/show the right buttons.
        $inputGroup.find(TOGGLE_FIELD_PROPS_CLASS).show();
        $inputGroup.find(FIELD_PROPS_RESET_CLASS).hide();

        event.preventDefault();
    }

    /**
     * Function to toggle the illegible action.
     * @param {object} event The click event.
     */
    function toggleTextDisplay(event) {
        $(this).find('span').toggle();

        event.preventDefault();
    }

    /**
     * Function for when fields have been changed in the approval stage.
     * @param {object} event The event object.
     * @param {object} $approvedDataField The jQuery object for the hidden field.
     */
    function approvalChange(event, $approvedDataField) {
        var $submitButton = $(SUBMIT_BUTTON);

        // Change the hidden approval input to a completed input.
        $approvedDataField.attr('name', 'completed_by_id');

        // Change the submit button visually.
        $submitButton.text('Save')
            .removeClass('btn-success')
            .addClass('btn-primary');

        $(event.target.form).find('.alert-approval').slideDown();

        // We only want to do all this once.
        backToApproval = true;
    }

    /**
     * This translates the awful object you get from serializeArray to
     * something usable.
     * @param {object} object The form item object
     * @param {object} item The key value pair.
     * @return {object} The formatted object.
     */
    function translateSeriasedData(object, item) {
        object[item.name] = item.value;

        return object;
    }

    /**
     * Checks dates and sets custom validity.
     * @param {string} first First date name.
     * @param {string} last Last date name.
     * @param {object} data The form data.
     * @param {object} input The current input element.
     */
    function compareDates(first, last, data, input) {
        // Check we have both the dates present.
        if (data[first] && data[last]) {
            var firstDate = new Date(data[first]),
                lastDate = new Date(data[last]);

            // Now do the check.
            if (fieldSpecialStates.indexOf(data[first]) !== -1 ||
                fieldSpecialStates.indexOf(data[last]) !== -1) {
                input.setCustomValidity('');
            } else if(firstDate > lastDate) {
                // Here is the error state.
                input.setCustomValidity('The `' + last + '` must be after the `' + first + '`.');
            } else {
                input.setCustomValidity('');
            }
        }
    }

    /**
     * Starts the JS tour.
     * @var {string} type The tour page type.
     */
    function startTour(type) {
        if (!type) return;

        // Instance the tour
        var tour = new Tour({
            name: 'tour-' + type,
            template: document.getElementById('tour-template').innerHTML,
            //storage: false,
            steps: tourConfig[type],
            onEnd: function(tour) {
                $('html, body').animate({scrollTop: 0}, 400);
            }
        });

        // Initialize and start the tour
        tour.init();
        tour.start();
    }

    /**
     * Exposed function for checking dates are in the right order.
     * @param {object} input The current input element.
     */
    exports.checkDates = function(input) {
        var i = 0,
            $form = $(input.form),
            toggleBtns = input.nextElementSibling.querySelectorAll('.toggle-field-props'),
            data = $form.serializeArray().reduce(translateSeriasedData, {});

        // We want to get the special states from the DOM.
        if (!fieldSpecialStates.length) {
            // Only if not cached.
            for (i; i < toggleBtns.length; i++) {
                fieldSpecialStates.push(toggleBtns[i].dataset.value);
            }
        }

        // Check the birth/death dates.
        compareDates('birth_date', 'death_date', data, input);
    }

    /**
     * Exposed function for toggling the child selection section.
     */
    exports.toggleChildForm = function() {
        var $toggleSections = $(CHILD_TOGGLE_SECTIONS),
            $requiredInputs = $toggleSections.find(REQUIRED_INPUT_SELCTOR);

        // Show and hide the right sections.
        $toggleSections.slideToggle();
        // Toggle the required inputs so they don't block the form submission.
        $requiredInputs.prop('disabled', function(i, v) { return !v; });

        if (!imageZoomsInitialised) {
            // Initialise image zoom and controls for the new stuff.
            wheelzoom(document.querySelectorAll(RETARD_IMAGE_ZOOM_SELECTOR));
            // (Only once)
            imageZoomsInitialised = true;
        }
    }

    /**
     * Event handler for mousemove to detect inactivity.
     */
    jQuery(document).ready(function($) {
        var $approvedDataField = $(APPROVAL_STAGE_DATA_FIELD),
            $transcribeDataField = $(TRANSCRIBE_STAGE_DATA_FIELD),
            $surveyTrigger = $(SURVEY_TRIGGER);

        // Listen to the field state buttons.
        $(TOGGLE_FIELD_PROPS_CLASS).on('click', toggleFieldProps);
        $(FIELD_PROPS_RESET_CLASS).on('click', fieldPropsReset);
        // And the datepicker mode (separate, see app.js)
        $(TOGGLE_DATEPICKER_MODE_CLASS).on('click', toggleTextDisplay);

        // This means we're in the approval stage.
        if ($approvedDataField.length > 0) {
            // Listen for changed inputs when
            $(this).on('change', INPUT_SELECTOR, function(event) {
                if (! backToApproval)
                    approvalChange(event, $approvedDataField);
            });

            // Start the approval tour.
            startTour('approve');
        } else if ($transcribeDataField.length > 0) {
            // Start the transcription tour.
            startTour('transcribe');
        }

        // Trigger the survey popup if the trigger is present.
        if ($surveyTrigger.length > 0) $surveyTrigger.click();
    });

    return exports;
}(TranscriptionForm || {}));
