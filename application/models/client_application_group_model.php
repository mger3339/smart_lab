<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_application_group_model extends Smartlab_model {


    public function get_groups($by,$application_id)
    {
        $groups = $this->get_many_by($by,$application_id);
        $application_groups = array();

        foreach($groups as $group)
        {
            $application_groups[] = $group->group_id;
        }
        return $application_groups;
    }

    public function delete_application_groups($application_groups) {

        $delete = array();

        foreach($application_groups as $application_group) {
            $delete[] = $this->_database->delete('client_application_groups', array('id' => $application_group->id));
        }

        return $delete;
    }



}