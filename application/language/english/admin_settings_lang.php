<?php

$lang['admin_settings_title']			= 'Settings';


$lang['admin_auto_register_title']		= 'User self-registration';

$lang['admin_auto_register_intro']
			= '<p>Enabling user self-registration allows users to register and add themselves to the user-base by simply logging for the first time in with their email and the <em>Self-registration password</em>.</p><p>Note that to enable user self-registration at least one <em>Valid email hostname</em> must be set up in order to have a means of validating users when they register.</p>';

$lang['admin_auto_register_label']
			= 'Allow users to self-register?';

$lang['admin_auto_register_checkbox_label']
			= 'User self-registration';

$lang['admin_auto_register_password_label']
			= 'Self-registration password';

$lang['admin_auto_register_password_confirm_label']
			= 'Re-type self-registration password';


$lang['admin_email_hostnames_title']	= 'Valid email hostnames';

$lang['admin_email_hostnames_intro']
			= '<p>Valid email hostnames will be checked against when any attempts are made to add or edit a user’s email (by both admin and users themselves). For example, if the hostname <em>mydomain.com</em> is added, any attempts to add or edit emails would have to conform to <em>[name]@mydomain.com</em>.</p><p>At least one valid email hostname must be set up to enable <em>' . $lang['admin_auto_register_title'] . '</em>.</p>';

$lang['admin_add_email_hostname_action_title']
			= 'Add a new valid email hostname:';

$lang['admin_add_email_hostname_success_message']
			= 'Email hostname added successfully.';

$lang['admin_add_email_hostname_error_message']
			= 'There were errors when attempting to add the email hostname.';

$lang['admin_update_email_hostname_success_message']
			= 'Email hostname updated successfully.';

$lang['admin_update_email_hostname_error_message']
			= 'There were errors when attempting to update the email hostname.';

$lang['admin_delete_email_hostname_success_message']
			= 'Email hostname deleted successfully.';

$lang['admin_delete_email_hostname_error_message']
			= 'An error occured when attempting to delete the email hostname.';