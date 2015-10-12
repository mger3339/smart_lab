<div id="us_page">
    <div class="admin-content users">

        <h2><?php echo lang('admin_users_title'); ?> <span>(<?php echo $total_users; ?>)</span></h2>

        <div class="add-row">
            <button class="add-row-btn" data-url="<?php echo "admin/users/add"; ?>"><?php echo lang('admin_users_button_label_add'); ?></button>
            <button class="import-row-btn" data-url="<?php echo "admin/users/import"; ?>"><?php echo lang('admin_users_button_label_import'); ?></button>
            <div class="clear"></div>
            <ul class="data-rows-list">
                <li class="data-row"></li>
            </ul>
        </div>

        <div class="search_section">

            <h2>Search options</h2>
            <?php echo form_open("admin/users/search/",array('class'=>'search_users_form','method'=>'get')); ?>
            <div class="control-group">
                <label>Free text search</label>
                <input name="free_text" type="text" value="<?php echo $this->input->get('free_text'); ?>">
            </div>

            <div class="control-group">
                <label>Role</label>
                <?php $options = array('--Select user role--'); ?>
                <?php foreach($all_roles as $key => $value): ?>
                    <?php $options[$key] = $value; ?>
                <?php endforeach; ?>
                <?php echo form_dropdown('user_role',$options,$this->input->get('user_role')); ?>
            </div>

            <div class="control-group">
                <label>Group</label>
                <?php $options = array('--Select group--'); ?>
                <?php foreach($this->client->groups as $group): ?>
                    <?php $options[$group->id] = $group->name; ?>
                <?php endforeach; ?>
                <?php echo form_dropdown('group_id',$options,$this->input->get('group_id')); ?>
            </div>

            <div class="control-group">
                <label>Country</label>
                <?php $options = array('--Select country--'); ?>
                <?php foreach($country_options as $key => $country): ?>
                    <?php $options[$key] = $country; ?>
                <?php endforeach; ?>
                <?php echo form_dropdown('country_iso',$options,$this->input->get('country_iso')); ?>
            </div>
            <div class="control-group">
                <label>Timezone</label>
                <?php $options = array('--Select timezone--'); ?>
                <?php foreach($time_zone_options as $key => $timezone): ?>
                    <?php $options[$key] = $timezone; ?>
                <?php endforeach; ?>
                <?php echo form_dropdown('time_zone',$options,$this->input->get('time_zone')); ?>
            </div>
            <div class="control-group">
                <label>City</label>
                <input name="city" type="text" value="<?php echo $this->input->get('city'); ?>">
            </div>



            <input class="search_btn" type="submit" value="Search">
            <button class="reset_search search_btn">Reset search</button>

            <div class="clear"></div>

            <?php echo form_close(); ?>

        </div>
        <div class="users_options">
            <div>
                <button class="select_all_users">Select All</button>
                <button class="deselect_all_users">Deselect All</button>
                <button class="edit_user_role" data-url="<?php echo 'admin/users/edit_user_role'; ?>">Edit User Role</button>
                <button class="edit_user_group" data-url="<?php echo 'admin/users/edit_groups'?>">Edit Groups</button>

                <?php echo form_dropdown('per_page',$per_page_option,$per_page,'id="per_page" class="per_page" data-url="admin/users"'); ?>
            </div>
            <div class="clear"></div>
            <ul class="data-rows-list">
                <li class="data-role-row"></li>
            </ul>
            <div id="pagination_user">
                <?=$pagination?>
            </div>
        </div>
        <div class="clear"></div>
        <ul class="data-rows-list" id="users_list">
<!--            <pre>-->
<!--                --><?php //print_r($users); die; ?>
            <?php foreach ($users as $row): ?>
                <li class="data-row">
                    <?php echo $this->load->view('admin/users/partials/user_row', array(
                        'row'					=> $row,
                    ), TRUE); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>