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
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once($CFG->libdir.'/formslib.php');

class generate_keys_form extends moodleform {

    protected function definition() {

        $mform =& $this->_form;
        $courselist = $this->_customdata['courselist'];
        $instance = $this->_customdata['instance'];

        $mform->addElement('hidden', 'instance', $instance);
        $mform->setType('instance', PARAM_INT);
        
        $mform->addElement('header', '', get_string('generate_header', 'block_eledia_multikeys'), 'eledia_generate_keys');

        $mform->addElement('select', 'course', get_string('course'), $courselist);
        $mform->addRule('course', null, 'required', null, 'client');
        $mform->setType('course', PARAM_INT);

        $mform->addElement('text', 'count', get_string('choose_count', 'block_eledia_multikeys'),
                'maxlength="10" size="6"');
        $mform->addRule('count', null, 'required', null, 'client');
        $mform->setType('count', PARAM_INT);

        $mform->addElement('text', 'mail',  get_string('choose_mail', 'block_eledia_multikeys'),
                'maxlength="100" size="25"');
        $mform->addRule('mail', null, 'required', null, 'client');
        $mform->setType('mail', PARAM_EMAIL);

        $mform->addElement('text', 'length',  get_string('choose_length', 'block_eledia_multikeys'),
                'maxlength="3" size="6"');
        $mform->setDefault('length', 10);
        $mform->setType('length', PARAM_INT);

        $mform->addElement('text', 'prefix',  get_string('prefix', 'block_eledia_multikeys'), 'maxlength="10" size="6"');
        $mform->setType('prefix', PARAM_ALPHANUMEXT);

        $this->add_action_buttons(true, get_string('generate_keys', 'block_eledia_multikeys'));
    }

    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        if (empty($data['course'])) {
            $errors['course'] = get_string('missingcourse', 'block_eledia_multikeys');
        } else {
            if ( !$DB->get_records('enrol', array('courseid' => $data['course'], 'enrol' => 'elediamultikeys') )) {
                $errors['course'] = get_string('course_notuses_multikeys', 'block_eledia_multikeys');
            }
        }

        if (($data['length'] + strlen($data['prefix'])) > 99) {
            $errors['length'] = get_string('keytoolong', 'block_eledia_multikeys');
        }

        foreach (array('count', 'length') as $field) {
            if (!is_numeric($data[$field]) or (int) $data[$field] <= 0) {
                $errors[$field] = get_string('wrong_number', 'block_eledia_multikeys');
            }
        }

        if (isset($data['mail'])) {
            if (! validate_email($data['mail'])) {
                $errors['mail'] = get_string('invalidemail');
            }
        }

        return $errors;
    }

}
