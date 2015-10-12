<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_group_model extends Smartlab_model {


    /**
     * Enable soft delete
     */
    protected $soft_delete = TRUE;


    /**
     * Protected columns
     */
    public $protected_attributes = array( 'id' );


    /**
     * Prototype for row creation
     */
    private $prototype = array(
        'client_id'			=> NULL,
        'name'				=> NULL,
    );


    /**
     * Default groups container
     */
    private $default_groups = array();


    /**
     * Cache container for row storage
     */
    private $cache = array();


    /**
     * Model validation rules
     */
    public $validate = array(
        array(
            'field'		=> 'client_id',
            'label'		=> 'client id',
            'rules'		=> 'required|trim|integer'
        ),
        array(
            'field'		=> 'name',
            'label'		=> 'group name',
            'rules'		=> 'required|trim|max_length[60]|is_client_unique[client_groups.name]'
        ),
    );


    /**
     * Model observers
     */
    public $before_delete		= array('purge_cache', 'delete_dependencies');
    public $after_get			= array('add_default_properties');


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get a client's groups
     *
     * @param	int
     * @return	array
     */
    public function get_client_group($client_id)
    {
        if ( ! isset($this->cache[$client_id]) )
        {
            $this->order_by('sort_order', 'ASC');

            $groups_data = $this->get_many_by('client_id', $client_id);

            $client_groups = array();

            // purge any groups that may no longer exist in the system
            foreach ($groups_data as $row)
            {
                $client_groups[$row->id] = $row;
            }
            $this->cache[$client_id] = $client_groups;

        }

        return $this->cache[$client_id];
    }



    /**
     * Purge the cached rows
     *
     * @param	mixed
     * @return	mixed
     */
    public function purge_cache($data = NULL)
    {
        $this->cache = array();

        return $data;
    }


    /**
     * Return the model prototype
     *
     * @param	int
     * @return	object
     */
    public function create_prototype($client_id = NULL)
    {

        $this->prototype['client_id'] = $client_id;
        $this->prototype['name'] = "Group name";

        return (object) $this->prototype;
    }


    /**
     * Define extra client group row properties
     *
     * @param	object
     * @return	object
     */
    protected function add_default_properties($row)
    {
        if (is_object($row))
        {
            $row->original_name = "Group Name";
        }

        return $row;
    }



    /**
     * Set or modify post data on create
     *
     * @param	array
     * @return	array
     */
    protected function modify_post_data($data)
    {
        // unset the unique_id if set
        if ( isset($data['unique_id']) )
        {
            unset($data['unique_id']);
        }

        // set the sort order
        if ( isset($data['client_id']) )
        {
            $data['sort_order'] = $this->count_by('client_id', $data['client_id']) + 1;
        }

        return $data;
    }


    /**
     * Set or modify post data on create
     *
     * @param	int
     * @param	string
     * @return	void
     */
    public function update_sort_order($client_id, $group_ids)
    {
        $group_ids = explode('-', $group_ids);

        $sort_order = 1;

        foreach ($group_ids as $group_id)
        {
            $this->_database->where('client_id', $client_id);

            $this->update($group_id, array('sort_order' => $sort_order), TRUE);

            $sort_order++;
        }
    }


    /**
     * @param array
     * @return array
     */

    public function get_groups_by_id($id)
    {
        $this->_database->where_in('id',$id);
        $this->_database->where_in('deleted',0);
        $query = $this->_database->get('client_groups');
        $query->result_array();

        return $query->result_array();
    }

    /**
     * Return the all user groups as an array
     * for a form select element
     *
     * @return	array
     */
    public function get_groups_select_options()
    {
        $options = array();

        foreach ($this->$groups as $group => $attr)
        {
            $options[$group] = $attr['name'];
        }

        return $options;
    }


}