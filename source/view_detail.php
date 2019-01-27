<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

// @todo DO NO CHANGE THE CODING FROM HERE TO THE NEXT TO-DO COMMENT!

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n = optional_param('n', 0, PARAM_INT);  // ... testmodule instance ID - it should be named as the first character of the module.

if ($id) {
    $cm = get_coursemodule_from_id('testmodule', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $testmodule = $DB->get_record('testmodule', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $testmodule = $DB->get_record('testmodule', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $testmodule->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('testmodule', $testmodule->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_testmodule\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $testmodule);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/testmodule/view_detail.php', array('id' => $cm->id));
$PAGE->set_title(format_string($testmodule->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($testmodule->intro) {
    echo $OUTPUT->box(format_module_intro('testmodule', $testmodule, $cm->id), 'generalbox mod_introbox', 'testmoduleintro');
}

// @todo NOW YOU CAN CHANGE THE CODING!
// @todo Replace the following lines with you own code.

global $SESSION;

echo $OUTPUT->heading('Details');

// Implement form for user
require_once(__DIR__ . '/forms/detail_form.php');

//Tabelle
$table = new html_table();
$table->head = array('ID', 'Name', 'Email');
//Datensatz zuweisen

$id = $SESSION->formdata->id;
$name = $SESSION->formdata->name;
$email = $SESSION->formdata->email;

//Daten zuweisen an HTML-Tabelle
$table->data[] = array($id, $name, $email);

//Tabelle ausgeben
echo html_writer::table($table);

//Form
$mform = new detail_form();
$mform->render();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Remove SESSION data for form
    unset($SESSION->formdata);
    // Redirect to the course main page.
    $returnurl = new moodle_url('/mod/testmodule/view.php', array('id' => $cm->id));
    redirect($returnurl);

    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    // Redirect to the course result page.
    $returnurl = new moodle_url('/mod/testmodule/view_end.php', array('id' => $cm->id));
    redirect($returnurl);
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    // Set default data (if any)
    // Required for module not to crash as a course id is always needed
    $formdata = array('id' => $id);
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
}

// Finish the page.
echo $OUTPUT->footer();
