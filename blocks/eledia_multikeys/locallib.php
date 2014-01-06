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
defined('MOODLE_INTERNAL') or die('Direct access to this script is forbidden.');

define('ELEDIA_MULTIKEYS_KEYS_MAX_KEY_COUNT', 200);

class eledia_multikeys_service {

    public function generate_password($length=9, $strength=0) {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength > 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength > 2) {
            $vowels .= "AEUY";
        }
        if ($strength > 4) {
            $consonants .= '23456789';
        }
        if ($strength > 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    public function generate_key($newkeys, $oldkeys, $prefix, $keycount = 0, $length=10, $strength = 7) {
        static $i;

        if (empty($i)) {
            $i = 0;
        }
        $keycount = $keycount + ELEDIA_MULTIKEYS_KEYS_MAX_KEY_COUNT;
        if ($i++ > $keycount) {
            print_error('to much new keys');
        }

        $newkey = $this->generate_password($length, $strength);
        if (in_array($prefix.'_'.$newkey, $newkeys) || in_array($prefix.'_'.$newkey, $oldkeys)) {
            $newkey = $this->generate_key($newkeys, $oldkeys, $prefix, $keycount, $length, $strength);
        }
        return $newkey;
    }

    public function send_enrolkeys_email($adress, $keylist, $course, $filename) {
        global $CFG;

        $user = new stdClass();
        $user->id          = guest_user()->id;
        $user->lang        = current_language();
        $user->firstaccess = time();
        $user->mnethostid  = $CFG->mnet_localhost_id;
        $user->email       = $adress;

        $site = get_site();
        $supportuser = core_user::get_support_user();

        $data = new stdClass();
        $data->firstname = fullname($user);// String für Anrede firmenname?
        $data->sitename = format_string($site->fullname);
        $data->admin = generate_email_signoff();
        $data->course = $course->fullname.' ('.$course->shortname.')';
        $data->keylist = $keylist;

        $data->link = $CFG->wwwroot;

        $subject = get_string('email_enrolkeys_subject', 'block_eledia_multikeys');

        $message     = get_string('email_enrolkeys_message', 'block_eledia_multikeys', $data);
        $messagehtml = text_to_html(get_string('email_enrolkeys_message', 'block_eledia_multikeys',
                $data), false, false, true);

        $attachedfilename = 'keylist_course_'.$course->id.'.csv';
        $user->mailformat = 1;  // Always send HTML version as well.

        $return = email_to_user($user, $supportuser, $subject, $message, $messagehtml, $filename, $attachedfilename);
        $filename = trim($filename, '/');
        unlink($CFG->dataroot.'/'.$filename); // We don't need this file anymore.
        return $return;
    }

    public function create_keylist($formdata) {
        global $DB;

        $courseid = $formdata->course;
        $count = $formdata->count;
        $mail = $formdata->mail;
        $length = $formdata->length;
        $prefix = trim($formdata->prefix);

        $keystable = $DB->get_records('block_eledia_multikeys', array('user' => null));
        $oldkeys = array();
        if ($keystable) {
            foreach ($keystable as $keyraw) {
                $oldkeys[] = $keyraw->code;
            }
        }

        $newkeys = array();
        for ($i = 0; $i < $count; $i++) {
            if ($length) {
                $newkey = $this->generate_key($newkeys, $oldkeys, $prefix, $count, $length);
            } else {
                $newkey = $this->generate_key($newkeys, $oldkeys, $prefix, $count);
            }
            if (!empty($prefix)) {
                $newkey = $prefix.'_'.$newkey;
            }

            $newkeys[] = $newkey;
            $newkeyobj = new stdClass();
            $newkeyobj->course = $courseid;
            $newkeyobj->code = $newkey;
            $newkeyobj->user = null;
            $newkeyobj->mailedto = $mail;
            $newkeyobj->timecreated = time();
            $DB->insert_record('block_eledia_multikeys', $newkeyobj);
        }

        if (count($newkeys) > 0) {
            return $newkeys;
        }
        return false;
    }

    public function create_csv($keys) {
        global $CFG;

        $keydir = $CFG->dataroot.'/temp/coursekeys';
        if (!is_dir($keydir)) {
            if (!mkdir($keydir, $CFG->directorypermissions, true)) {
                print_error('could not create temp dir');
            }
        }
        $filename = tempnam($keydir, 'key_');
        unlink($filename); // We don't need this temp file. We create a new one with .csv extension.

        $relativename = str_replace($CFG->dataroot, '', $filename);

        $f = fopen($filename, "wb");

        foreach ($keys as $key) {
            fwrite($f, $key."\r\n");
        }
        fclose($f);
        return $relativename;
    }
}
