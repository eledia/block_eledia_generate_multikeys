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
$string['back'] = 'Back';

$string['choose_count'] = 'Number of keys to create';
$string['choose_course'] = 'Choose course';
$string['choose_length'] = 'Length of keys to create (number of signs)';
$string['choose_mail'] = 'The mail address the keys are sent to';
$string['course_notuses_multikeys'] = 'Choosen course does not use multikey enrol';

$string['eledia_multikeys:addinstance'] = 'Add generate multikeys block';
$string['eledia_multikeys:view'] = 'Use generate multikeys block';
$string['email_send'] = 'The new keys were sent to the given mail address';
$string['email_enrolkeys_subject'] = 'Your enrollment keys';
$string['email_enrolkeys_message'] = 'Hello {$a->firstname},

we have created enrollment keys for a course in the Moodle system \'{$a->sitename}\'.

Your generated keys are:

{$a->keylist}

Each key can be used one time only for the defined course.

With this keys you are able to enroll on following site in course {$a->course} :

{$a->link}

In most mail programs, this should appear as a blue link
which you can just click on.  If that doesn\'t work,
then cut and paste the address into the address
line at the top of your web browser window.

If you need help, please contact the site administrator,
{$a->admin}';

$string['generate_keys'] = 'Generate keys';
$string['generate_header'] = 'Generate keys parameters';

$string['keys_header'] = 'Generated keys';
$string['keytoolong'] = 'Keys should not be longer than 99 characters, including the prefix.';

$string['missingcourse'] = 'Course is missing';
$string['missingcount'] = 'Number of keys is missing';
$string['missingmail'] = 'Mail address is missing';

$string['pluginname'] = 'One time course key enrollment';
$string['prefix'] = 'Key prefix';

$string['title'] = 'One time course key enrollment';

$string['wrong_mail_format'] = 'The mail address you used is not a valid mail address';
$string['wrong_number'] = 'Wrong number';
