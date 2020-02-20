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
 * Strings for component 'privacy_provider_template', language 'en'.
 *
 * @package    privacy_provider_template
 * @copyright  2019 eLeDia
 * @author     Urs Hunkler {@link urs.hunkler@eledia.de}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['userdata'] = 'The user data description.';
$string['privacy:metadata:database:eledia_plugin_action'] = 'Describe the action.';
$string['privacy:metadata:database:eledia_plugin_action:userid'] = 'The ID of the user whose data was saved.';
$string['privacy:metadata:database:eledia_plugin_action:userdata'] = 'The data which was saved.';
$string['privacy:metadata:database:eledia_plugin_action:timemodified'] = 'The time that content was modified.';

// For the »null_provider«.
$string['privacy:metadata'] = 'The privacy_provider_template does not store any personal data about any user.';

// For the "external_provider".
$string['privacy:metadata:unizensus_allrequests'] = 'Most requests sent to Unizensus, e.g. status queries triggered by viewing a plugin instance, contain the internal id and language of the user that triggered the request.';
$string['privacy:metadata:unizensus_allrequests:userid'] = 'The id of the user that triggered the query.';
$string['privacy:metadata:unizensus_allrequests:lang'] = 'The language of the user that triggered the query.';
$string['privacy:metadata:unizensus_createevaluation'] = 'Creating an Unizensus evaluation requires some personal information about the trainer to be sent to Unizensus, like their name and email address.';
$string['privacy:metadata:unizensus_createevaluation:lang'] = 'Language of the trainer.';
$string['privacy:metadata:unizensus_createevaluation:firstname'] = 'Firstname of the trainer.';
$string['privacy:metadata:unizensus_createevaluation:lastname'] = 'Lastname of the trainer.';
$string['privacy:metadata:unizensus_createevaluation:email'] = 'Email of the trainer.';
$string['privacy:metadata:unizensus_createevaluation:username'] = 'Username of the trainer.';
