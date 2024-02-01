<?php

namespace adz\Stax;

class GF_Payment
{
    public function __construct()
    {
        // Actions
        add_action('gform_after_submission', [$this, 'add_note_to_entry'], 10, 2);

        // Filters
        // add_filter('gform_submit_button', function($button, $form) {
        //     return '';
        // }, 10, 2);
    }

    public function add_note_to_entry($entry, $form)
    {
        $note = 'Payment successful'; // set the note text
        // \GFAPI::add_note($entry['id'], $entry['form_id'], $note); // add the note to the entry
        \GFAPI::add_note($entry['id'], $user_id = false, $user_name = "adz Stax Payment", $note, $note_type = 'adz_stax', $sub_type = null);
        // \GFAPI::send_notifications( $form, $entry, 'complete_payment' );
    }
}
