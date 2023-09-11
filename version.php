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
 * Version file for local_ws_logdata.
 *
 * @package     local_ws_logdata
 * @copyright   2023 onwards, Te Wānanga o Aotearoa
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// No direct access.
defined('MOODLE_INTERNAL') || die();

// This plugin requires Moodle 4.0.
$plugin->requires = 2022041900;

// Plugin details.
$plugin->component  = 'local_ws_logdata';
$plugin->version    = 2023090403;   // Plugin created September 2023.
$plugin->release    = 'v4.0.1';

// Plugin status details.
$plugin->maturity = MATURITY_RC;   // ALPHA, BETA, RC, STABLE.
