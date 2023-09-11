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
 * Webservice file for local_ws_logdata.
 *
 * @package     local_ws_logdata
 * @copyright   2023 onwards, Te Wānanga o Aotearoa
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_logdata\webservices;

use \external_value;
use \external_single_structure;
use \external_multiple_structure;

// No direct access.
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Class userLogins
 *
 * @package     local_ws_logdata
 * @copyright   2023 onwards, Te Wānanga o Aotearoa
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_logins extends \external_api {

    CONST PAGESIZE = 10000;
    CONST DAYS = 5;

    /**
     * Validate incoming parameters.
     * @return \external_function_parameters
     */
    public static function get_user_logins_parameters() {
        return new \external_function_parameters(
            array(
                'days' => new \external_value(PARAM_INT, 'The number of days to look back', VALUE_DEFAULT, user_logins::DAYS),
                'pagesize' => new \external_value(PARAM_INT, 'The max number of results', VALUE_DEFAULT, 0),
                'page' => new \external_value(PARAM_INT, 'Page number of set', VALUE_DEFAULT, 1),
            )
        );
    }

    /**
     * Get user login data from the standard logstore.
     * @param int $days The number of days looking back from now that we want data for.
     * @param int $pagesize The max number of results.
     * @param int $page Page number of set.
     * @return array
     * @throws \dml_exception
     */
    public static function get_user_logins($days, $pagesize, $page): array {
        global $DB;
        // The logstore_standard_log table can be really big.
        // So we save the ID of the first record in our subset so subsequent calls are faster.
        $startid = 1;
        $lastdays = get_config('local_ws_logdata', 'days');
        $xdaysago = time() - $days * 86400;
        if ($days <= $lastdays) {
            $startid = get_config('local_ws_logdata', 'startid');
        }
        // Use last pagination size if none specified.
        if (!$pagesize) {
            $lastpagesize = get_config('local_ws_logdata', 'pagesize');
            $pagesize = $lastpagesize >= 1 ? $lastpagesize : user_logins::PAGESIZE;
        }
        $offset = $pagesize * ($page-1);

        $sql = "SELECT id,
        userid,
        timecreated as timecreatedbi,
        courseid,
        contextlevel,
        action,
        target
        from {logstore_standard_log}
        WHERE action = ?
        AND timecreated >= ?
        AND id >= ?
        ORDER BY timecreated ASC";

        $records = $DB->get_records_sql($sql, ['loggedin', $xdaysago, $startid], $offset, $pagesize);

        // Save the last request info.
        set_config('days', $days, 'local_ws_logdata');
        set_config('pagesize', $pagesize, 'local_ws_logdata');
        if ($page == 1) {
            set_config('startid', array_key_first($records), 'local_ws_logdata');
        }

        // Do the date parsing in PHP to be DB agnostic.
        foreach ($records as $record) {
            $record->timecreatednz = date('Y-m-d H:i:s', $record->timecreatedbi);
            $record->year = date('Y', $record->timecreated);
        }

        return ['logins' => array_values($records)];
    }

    /**
     * Describe the returned data structure.
     * @return external_single_structure
     */
    public static function get_user_logins_returns() {
        $logins = new external_single_structure(
            array(
            'id' => new external_value(PARAM_INT, 'Index of the table record'),
            'userid' => new external_value(PARAM_INT, 'Unique identifier of the user'),
            'timecreatedbi' => new external_value(PARAM_INT, 'Unix timestamp'),
            'timecreatednz' => new external_value(PARAM_RAW, 'Timestamp'),
            'year' => new external_value(PARAM_INT, 'Year of the event'),
            'courseid' => new external_value(PARAM_INT, 'Unique identifier of the course'),
            'contextlevel' => new external_value(PARAM_INT, 'Context level the event occurs at'),
            'action' => new external_value(PARAM_TEXT, 'Short description of the event'),
            'target' => new external_value(PARAM_TEXT, 'Target')
        ));

        return new external_single_structure([
            'logins' => new external_multiple_structure($logins, 'List of login events'),
        ]);
    }

}
