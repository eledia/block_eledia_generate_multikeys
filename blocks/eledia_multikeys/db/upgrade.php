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
 * This file keeps track of upgrades to the eledia_usercleanup block
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 *
 * @param int $oldversion
 * @param object $block
 */
function xmldb_block_eledia_multikeys_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2013042900) {

        // Define table eledia_multikeys_keys to be renamed to block_eledia_multikeys
        $table = new xmldb_table('eledia_multikeys_keys');

        // Launch rename table for eledia_multikeys_keys
        $dbman->rename_table($table, 'block_eledia_multikeys');

        // block_eledia_multikeys savepoint reached
        upgrade_block_savepoint(true, 2013042900, 'eledia_multikeys');

    }

    if ($oldversion < 2015120901) {

        // Switch courseid to enrol id for all keys
        $keys = $DB->get_records('block_eledia_multikeys');
        foreach ($keys as $key) {
            $enrol = $DB->get_record('enrol', array('enrol' => 'elediamultikeys', 'courseid' => $key->course));
            if (empty($enrol)) {
                $DB->set_field('block_eledia_multikeys','course', 0, array('id' => $key->id));
            } else {
                $DB->set_field('block_eledia_multikeys','course', $enrol->id, array('id' => $key->id));
            }
        }

        // Define table eledia_multikeys_keys to be renamed to block_eledia_multikeys
        $table = new xmldb_table('block_eledia_multikeys');
        $field = new xmldb_field('course', XMLDB_TYPE_CHAR);

        // Launch rename table for eledia_multikeys_keys
        $dbman->rename_field($table, $field, 'enrolid');

        $field2 = new xmldb_field('enrol_duration', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'mailedto');

        // Conditionally launch add field enrol_duration.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // block_eledia_multikeys savepoint reached
        upgrade_block_savepoint(true, 2015120901, 'eledia_multikeys');
    }

    return true;
}