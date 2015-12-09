<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 *
 * @package block
 * @category eledia_multikeys
 * @copyright 2013 eLeDia GmbH {@link http://www.eledia.de}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once('locallib.php');
require_once('generate_keys_form.php');

$action = optional_param('action', false, PARAM_ALPHA);
$instance = required_param('instance', PARAM_INT);

require_login();
require_capability('block/eledia_multikeys:view', context_block::instance($instance));

$myurl = new moodle_url($FULLME);

$PAGE->set_url($myurl);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');

// Get all courses which have an elediamultikeys enrol instance.
$sql = "SELECT e.*, c.fullname
    FROM {course} c, {enrol} e
    WHERE e.courseid = c.id
    AND enrol = 'elediamultikeys'
    GROUP BY e.id
    ORDER BY e.courseid ASC";
$enrol = $DB->get_records_sql($sql);

// Build the courselist for the formular.
$enrol_instances = array();
$strchoose = get_string('choose_course', 'block_eledia_multikeys');
$enrol_instances[0] = $strchoose;
foreach ($enrol as $enrolment) {
    $enrol_plugin = enrol_get_plugin('elediamultikeys');
    $enrol_instances[$enrolment->id] = $enrolment->fullname.':'.$enrol_plugin->get_instance_name($enrolment);
}

$keyservice = new eledia_multikeys_service();
$mform = new generate_keys_form(null, array('enrol_instances' => $enrol_instances, 'instance' => $instance));

if ($mform->is_cancelled()) {
    redirect($CFG->httpswwwroot.'/index.php');
}

if ($formdata = $mform->get_data()) {
    confirm_sesskey();
    // Get enrol instance for this course.
    $einstance = $DB->get_record('enrol', array('id' => $formdata->enrol_instance));
    if (empty($einstance)) {
        notice('Einschreibungs Instanz nicht gefunden.',
            $CFG->wwwroot.'/blocks/eledia_multikeys/generate_keys.php?instance='.$formdata->instance);
    }
    if ($newkeys = $keyservice->create_keylist($formdata)) {
        $csvfile = $keyservice->create_csv($newkeys);
        $keysoutput = implode("\n", $newkeys);
        if ($keyservice->send_enrolkeys_email($formdata->mail, $keysoutput, $enrol[$formdata->enrol_instance], $csvfile)) {
            $myurl->params(array('action' => 'continue', 'instance' => $instance, 'saved' => true));
            $SESSION->coursekeys = new stdClass();
            $SESSION->coursekeys->formdata = $formdata;
            redirect($myurl, get_string('email_send', 'block_eledia_multikeys'), 3);
            die; // Never reached.
        } else {
            print_error('error on sending the email');
	}
    } else {
        print_error('error on creating keylist');
    }
    exit;
}

if ($action == 'continue') {
    if (!empty($SESSION->coursekeys->formdata)) {
        $formdata = $SESSION->coursekeys->formdata;
        if (empty($formdata->enrol_duration)) {
            unset($formdata->enrol_duration);
        }
        $mform->set_data($formdata);
    }
} else {
    if (!empty($SESSION->coursekeys->formdata)) {
        unset($SESSION->coursekeys);
    }
}

$PAGE->navbar->add('multiplekeys');

$header = get_string('generate_keys', 'block_eledia_multikeys');
$PAGE->set_heading($header);

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
