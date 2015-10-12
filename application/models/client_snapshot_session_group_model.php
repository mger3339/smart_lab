<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_snapshot_session_group_model extends Smartlab_model {


    public function get_groups($by,$session_id)
    {
        $groups = $this->get_many_by($by,$session_id);
        $session_groups = array();

        foreach($groups as $group)
        {
            $session_groups[] = $group->group_id;
        }
        return $session_groups;
    }

    public function delete_session_groups($session_groups) {

        $delete = array();

        foreach($session_groups as $session_group) {
            $delete[] = $this->_database->delete('client_snapshot_session_groups', array('id' => $session_group->id));
        }

        return $delete;
    }



}