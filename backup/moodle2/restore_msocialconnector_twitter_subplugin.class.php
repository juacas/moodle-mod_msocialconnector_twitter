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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/* ***************************
 * Module developed at the University of Valladolid
 * Designed and directed by Juan Pablo de Castro at telecommunication engineering school
 * Copyright 2017 onwards EdUVaLab http://www.eduvalab.uva.es
 * @author Juan Pablo de Castro
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package msocial
 * *******************************************************************************
 */
defined('MOODLE_INTERNAL') || die();

class restore_msocialconnector_twitter_subplugin extends restore_subplugin {

    /**
     * Returns array the paths to be handled by the subplugin at msocial level
     *
     * @return array
     */
    public function define_msocial_subplugin_structure() {
        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $elename = $this->get_namefor('tweets');
        // We used get_recommended_name() so this works.
        $elepath = $this->get_pathfor('/tweets');
        $paths[] = new restore_path_element($elename, $elepath);
        $elename = $this->get_namefor('twtoken');
        $elepath = $this->get_pathfor('/twtoken');
        $paths[] = new restore_path_element($elename, $elepath);
        if ($userinfo) {
            $elename = $this->get_namefor('status');
            $elepath = $this->get_pathfor('/tweets/status');
            $paths[] = new restore_path_element($elename, $elepath);
        }
        return $paths;
    }

    public function process_msocialconnector_twitter_twtoken($data) {
        global $DB;

        $data = (object) $data;

        $data->msocial = $this->get_new_parentid('msocial');

        $newitemid = $DB->insert_record('msocial_twitter_tokens', $data);
    }

    public function process_msocialconnector_twitter_status($data) {
        global $DB;

        $data = (object) $data;

        $data->msocial = $this->get_new_parentid('msocial');
        $data->userid = isset($data->userid) ? $this->get_mappingid('user', $data->userid) : null;

        $newitemid = $DB->insert_record('msocial_tweets', $data);
        // No need to save this mapping as far as nothing depend on it
        // (child paths, file areas nor links decoder).
    }

    public function process_msocialconnector_twitter_tweets($data) {
        global $DB;
        $data->msocial = $this->get_new_parentid('msocial');
    }
}
