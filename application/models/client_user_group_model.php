<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_user_group_model extends Smartlab_model {


    public function get_groups($by,$user_id)
    {
        $groups = $this->get_many_by($by,$user_id);
        $user_groups = array();

        foreach($groups as $group)
        {
            $user_groups[] = $group->group_id;
        }
        return $user_groups;
    }

    public function delete_user_groups($user_groups) {

        $delete = array();

        foreach($user_groups as $user_group) {
           $delete[] = $this->_database->delete('client_user_groups', array('id' => $user_group->id));
        }

        return $delete;
    }

    public function delete_user_group_by_id($user_id,$group_id)
    {
        $this->_database
            ->where('user_id', $user_id)
            ->where('group_id', $group_id)
            ->delete('client_user_groups');
    }



}