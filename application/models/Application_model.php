<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application_model extends CI_Model {


	/**
	 * Applications data:
	 * Given that adding / deleting applications means
	 * modifying the code base, the applications list is
	 * stored here as hard data rather than in the DB.
	 *
	 */
	private $applications = array(
		
		/*
		'innovation-hopper' => array(
					'name'				=> 'Innovation Hopper',
					'is_admin'			=> FALSE,
					'modules'			=> NULL,
					'default_modules'	=> NULL,
		),
		
		'portal' => array(
					'name'				=> 'Portal',
					'is_admin'			=> FALSE,
					'modules'			=> array(
													'home'				=> 'Home',
													'discussion'		=> 'Discussion',
													'resources'			=> 'Resources',
													'community'			=> 'Community',
													'news'				=> 'News',
					),
					'default_modules'	=> array('home', 'resources', 'community'),
		),
		*/
		
		'timeline' => array(
					'name'				=> 'Timeline',
					'is_admin'			=> FALSE,
					'modules'			=> NULL,
					'default_modules'	=> NULL,
		),
		
		'trend-compass' => array(
					'name'				=> 'Trend Compass',
					'is_admin'			=> FALSE,
					'modules'			=> NULL,
					'default_modules'	=> NULL,
		),

	);

    private $application_colors = array(

        '888888' => 'None',
        '00A99D' => '00A99D',
        '29ABE2' => '29ABE2',
        '2E3192' => '2E3192',
        'D4145A' => 'D4145A',
        'ED1C24' => 'ED1C24',
        'F7931E' => 'F7931E',
        '8CC63F' => '8CC63F',
        '39B54A' => '39B54A',
    );

	/**
	 * Return the applications data
	 *
	 * @return	array
	 */
	public function get_all()
	{
		return $this->applications;
	}


	/**
	 * Return the applications dropdown options
	 *
	 * @return	array
	 */
	public function get_application_options()
	{
		$options = array();
		
		foreach ($this->applications as $application => $props)
		{
			$options[$application] = $props['name'];
		}
		
		return $options;
	}

    /**
     * Return the applications colours
     *
     * @return	array
     */
    public function get_application_colors()
    {
        return $this->application_colors;
    }

    /**
     * Return the applications colors dropdown options
     *
     * @return	array
     */
    public function get_application_colors_options()
    {
        $options = array();

        foreach ($this->application_colors as $application_colors => $props)
        {
            $options[$application_colors] = $props;
        }

        return $options;
    }

}