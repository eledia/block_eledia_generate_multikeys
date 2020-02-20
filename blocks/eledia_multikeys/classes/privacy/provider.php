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
 * Privacy Subsystem implementation for eledia_multikeys.
 *
 * @package    eledia_multikeys
 * @copyright  2020 eLeDia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_eledia_multikeys\privacy;

defined('MOODLE_INTERNAL') || die();

use \context;
use \core_privacy\local\request\approved_contextlist;
use \core_privacy\local\request\writer;
use \core_privacy\local\metadata\collection;
use \core_privacy\local\request\userlist;
use \core_privacy\local\request\approved_userlist;

/**
 * Privacy Subsystem implementation for eledia_multikeys.
 *
 * @copyright  2020 eLeDia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // The block_eledia_multikeys stores user provided data.
    \core_privacy\local\metadata\provider,
    // The eledia_multikeys provides data directly to core.
    \core_privacy\local\request\plugin\provider,
    // The eledia_multikeys is capable of determining which users have data within it.
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Returns information about how eledia_multikeys stores its data.
     *
     * @param collection $collection The initialised collection to add items to.
     *
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection): collection {

        // Example table.
        $collection->add_database_table('block_eledia_multikeys', [
            'enrolid' => 'privacy:metadata:database:block_eledia_multikeys:enrolid',
            'code' => 'privacy:metadata:database:block_eledia_multikeys:code',
            'user' => 'privacy:metadata:database:block_eledia_multikeys:user',
            'mailedto' => 'privacy:metadata:database:block_eledia_multikeys:mailedto',
            'enrol_duration' => 'privacy:metadata:database:block_eledia_multikeys:enrol_duration',
            'timecreated' => 'privacy:metadata:database:block_eledia_multikeys:timecreated',
            'timeused' => 'privacy:metadata:database:block_eledia_multikeys:timeused',
        ], 'privacy:metadata:database:block_eledia_multikeys');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     *
     * @return \core_privacy\local\request\contextlist $contextlist The contextlist containing the list of contexts used in
     *                                                  this plugin.
     */
    public static function get_contexts_for_userid(int $userid): \core_privacy\local\request\contextlist {
        // This plugin stored data directly against the userid
        // so we use the user context.
        $contextlist = new \core_privacy\local\request\contextlist();

        // Get the global user context.
        $contextlist->add_user_context($userid);

        return $contextlist;
    }

    /**
     * Get the list of users within a specific context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     * @throws \dml_exception
     */
    public static function get_users_in_context(userlist $userlist) {
        global $DB;

        // This function is used to tell if the user has data in the context of this plugin.
        // Since we adress the user by userid in this plugin we only have to check for this.
        $context = $userlist->get_context();

        // We store only in user context so catch all other calls.
        if (!$context instanceof \context_user) {
            return;
        }

        $user = $DB->get_record('user', array('id' => $context->instanceid));
        $has_data = false;

        // Make the check for each table.
        if ($DB->records_exists('block_eledia_multikeys', array('user' => $user->id))) {
            $has_data = true;
        }

        // If we have data add the user.
        if ($has_data) {
            $userlist->add_user($user->id);
        }
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        $user = $contextlist->get_user();
        foreach ($contextlist as $context) {
            // Get user records for table block_eledia_multikeys.
            $userdata = $DB->get_records('block_eledia_multikeys', array('user' => $user->id));
            foreach ($userdata as $data) {
                // Subcontext is used as folder for the exported data.
                $subcontext = [
                    get_string('userdata', 'eledia_multikeys'),
                    $data->id,
                ];
                unset($data->id);// Dont export the moodle internal id.
                writer::with_context($context)->export_data($subcontext, $data);
            }
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param   context $context The specific context to delete data for.
     *
     * @throws \dml_exception
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if (!$context instanceof \context_user) {
            return;
        }

        $user = $DB->get_record('user', array('id' => $context->instanceid));
        $DB->delete_records('block_eledia_multikeys', array('user' => $user->id));
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     *
     * @throws \dml_exception
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $userids = $userlist->get_userids();

        foreach ($userids as $userid) {
            $user = $DB->get_record('user', array('id' => $userid));
            $DB->delete_records('block_eledia_multikeys', array('user' => $user->id));
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist $contextlist The approved contexts and user information to delete information for.
     *
     * @throws \dml_exception
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $user = $contextlist->get_user();
        $DB->delete_records('block_eledia_multikeys', array('user' => $user->id));
    }
}
