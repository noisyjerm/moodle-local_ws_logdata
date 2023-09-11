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
 * Definition of webservices for local_ws_logdata.
 *
 * @package     local_ws_logdata
 * @copyright   2023 onwards, Te Wānanga o Aotearoa
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// No direct access.
defined('MOODLE_INTERNAL') || die();

// The individual capabilities.
$capabilities = 'report/log:view';

// We defined the web service functions to install.
$functions = [
    'local_ws_logdata_userlogins' => [
        'classname'     => '\local_logdata\webservices\user_logins',
        'methodname'    => 'get_user_logins',
        'classpath'     => 'local/ws_logdata/classes/webservices/user_logins.php',
        'description'   => 'Gets the user login event logs for a defined time period.',
        'capabilities'  => $capabilities,
        'type'          => 'read'
    ],
];

// We define the services to install as pre-built services. This is not editable by administrator.
$services = [
    'Log data export webservice' => [
        'functions'         => [
            'local_ws_logdata_userlogins',
        ],
        'restrictedusers'   => 1,
        'enabled'           => 1,
    ]
];
