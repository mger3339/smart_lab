<!--<pre>-->
<!--    --><?php //print_r($interest_options); die; ?>
<?php
$date = date_create();

date_timestamp_set($date, $user->last_login);
$a = date_format($date, 'l F');
$b = date_format($date, 'd.  Y');
?>
<?php echo form_open_multipart('user_account/update_account', array('id' => 'user-account-update', 'class' => 'user-account-form')); ?>
	
	<input type="hidden" name="unique_id" value="<?php echo $user->id; ?>" />
	
	<input type="hidden" name="client_id" value="<?php echo $user->client_id; ?>" />
    <div class="home_div">
        <span class="btn btn-default btn-file upload_button">
                Change profile image <input type="file" name="user_image">
        </span>
        <div class="edit_img">

        </div>
        <div class="section_1">

            <div class="control-group control-group-large">
                <label for="firstname">First name</label>
                <input
                    id="firstname"
                    type="text"
                    name="firstname"
                    value="<?php echo set_value('firstname', $user->firstname); ?>"
                    maxlength="60"
                />
                <?php echo form_error('firstname'); ?>
            </div>

            <div class="control-group control-group-large">
                <label for="lastname">Last name</label>
                <input
                    id="lastname"
                    type="text"
                    name="lastname"
                    value="<?php echo set_value('lastname', $user->lastname); ?>"
                    maxlength="60"
                />
                <?php echo form_error('lastname'); ?>
            </div>

            <div class="clear"></div>

            <div class="control-group control-group-large">
                <label for="lastname">Job title</label>
                <input
                    id="job_title"
                    type="text"
                    name="job_title"
                    value="<?php echo set_value('job_title', $user->lastname); ?>"
                    maxlength="60"
                    />
                <?php echo form_error('lastname'); ?>
            </div>

            <div class="clear"></div>

            <div class="control-group control-group-large">
                <label for="lastname">Department</label>
                <input
                    id="department"
                    type="text"
                    name="department"
                    value=""
                    maxlength="60"
                    />
                <?php echo form_error('lastname'); ?>
            </div>

            <div class="clear"></div>
            <div class="last_logged"><span class="last_logged_span">Last logged in:<br> <?php echo $a. "<br>" . $b; ?></span></div>
        </div>
    </div>

    <div class="section">
        <div class="label_section">Username:</div>
            <input
                id="username"
                type="text"
                name="username"
                value="<?php echo set_value('username', $user->username); ?>"
                maxlength="255"
                />
            <?php echo form_error('Username'); ?>

        <div class="clear"></div>

        <div class="label_section">E-mail:</div>
            <input
                id="email"
                type="text"
                name="email"
                value="<?php echo set_value('email', $user->email); ?>"
                maxlength="255"
                />
            <?php echo form_error('email'); ?>

        <div class="clear"></div>
		<div class="control-group control-group-wide">
            <div class="label_section">Timezone:</div>
			<?php echo form_dropdown('time_zone', $time_zone_options, $user->time_zone); ?>
			<?php echo form_error('time_zone'); ?>
		</div>
		
		<div class="clear"></div>
		<div class="control-group">
            <div class="label_section">Country:</div>
			<?php echo form_dropdown('country_iso', $country_options, $user->country_iso); ?>
			<?php echo form_error('country_iso'); ?>
		</div>
        <div class="clear"></div>
		<div class="control-group">
            <div class="label_section">Currency:</div>
			<?php echo form_dropdown('currency', $currency_options, $user->currency); ?>
			<?php echo form_error('currency'); ?>
		</div>

		<div class="clear"></div>

        <div class="form-btns">
            <button type="submit" name="update_account" value="TRUE">Update your account</button>
            <div class="clear"></div>
        </div>
	</div>

    <div class="section">
        <div class="label_section">Confirm new password:</div>
            <input
                id="password"
                type="password"
                name="password"
                value=""
                maxlength="60"
                autocomplete="off"
                />
            <?php echo form_error('password'); ?>

        <div class="clear"></div>
        <div class="label_section">Your new password:</div>
            <input
                id="new_password"
                type="password"
                name="new_password"
                value=""
                maxlength="60"
                autocomplete="off"
                />
            <?php echo form_error('new_password'); ?>
        <div class="clear"></div>
        <div class="form-btns">
            <button type="submit" name="update_password" value="TRUE">Change your password</button>
            <div class="clear"></div>
        </div>
    </div>
    <div class="groups">
        <div class="control-group control-group-large">
            <label for="groups">Groups</label>
            <input
                id="groups"
                type="text"
                name="groups"
                value=""
                maxlength="60"
                />
            <?php echo form_error('firstname'); ?>
        </div>
        <div class="control-group control-group-large">
            <label for="biography">Biography</label>
            <textarea
                id="biography"
                name="biography"
                cols="118"
                rows="5"
                >
            </textarea>
            <?php echo form_error('firstname'); ?>
        </div>
        <div class="expertise">
            <div class="label_section">Expertise:</div>
            <div class="add_expertise_field">
                <input
                    type="text"
                    name="expertise"
                    value=""
                    maxlength="60"
                    />

                <span class="add_expertise_input">+</span>
                <span class="add_expertise" data-url="<?php echo base_url('user_account/add_expertise') ?>">Add</span>
            </div>
            <?php foreach($expertise_options as $expertise): ?>
            <div class="expertise_name"><span class="expertise_span"><?php echo $expertise->expertise; ?></span><span class="close_expertise" id="<?php echo $expertise->id; ?>" data-delete-url="<?php echo base_url('user_account/delete_expertise') ?>">X</span></div>
            <?php endforeach; ?>
        </div>
        <hr/>
        <div class="interests">
            <div class="label_section">Interests:</div>
            <div class="add_interests_field">
                <input
                    id="interests"
                    type="text"
                    name="interests"
                    value=""
                    maxlength="60"
                    />
                <span class="add_interests_input">+</span>
                <span class="add_interests" data-url="<?php echo base_url('user_account/add_interests') ?>">Add</span>
            </div>
            <?php foreach($interest_options as $interests): ?>
            <div class="interests_name"><span class="interests_span"><?php echo $interests->interests; ?></span><span class="close_interests" id="<?php echo $interests->id; ?>" data-delete-url="<?php echo base_url('user_account/delete_interests') ?>>X</span></div>
            <?php endforeach; ?>
        </div>
    </div>
	
<?php echo form_close(); ?>