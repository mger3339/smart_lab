<?php $row_id = (property_exists($row, 'id')) ? 'client-application-' . $row->id : 'new-client-application'; ?>

<?php if ($action == 'add'): ?>
    <?php echo form_open("admin/applications/add", array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
    <?php echo form_open("admin/applications/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>

<?php if ($action_title): ?>
    <h3><?php echo $action_title; ?></h3>
<?php endif; ?>

    <div class="section">

        <?php if ($action == 'edit'): ?>
            <input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
        <?php endif; ?>

        <input type="hidden" name="client_id" value="<?php echo $row->client_id; ?>" />

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-name">Application name</label>
            <input
                id="<?php echo $row_id; ?>-name"
                class="client-application-name-input"
                type="text"
                name="name"
                value="<?php echo set_value('name', $row->name); ?>"
                maxlength="60"
                />
            <?php echo form_error('name'); ?>
        </div>

        <div class="control-group">
            <label for="<?php echo $row_id; ?>-application_colors">Application Color</label>
            <?php echo form_dropdown('accent_color', $applications_colors, $row->accent_color, array('class' => 'client-application-color-input')); ?>
            <?php echo form_error('accent_color'); ?>
        </div>

        <?php if ($action == 'add'): ?>
            <div class="control-group">
                <label for="<?php echo $row_id; ?>-application">Application</label>
                <?php echo form_dropdown('application', $applications, $row->application, array('class' => 'client-application-input')); ?>
                <?php echo form_error('application'); ?>
            </div>
        <?php elseif ($action == 'edit'): ?>
            <div class="control-group">
                <label>Application</label>
                <input
                    type="text"
                    name="original_name"
                    value="<?php echo $row->original_name; ?>"
                    disabled="disabled"
                    />
                <input type="hidden" name="application" value="<?php echo $row->application; ?>" />
            </div>
        <?php endif; ?>

        <div class="control-group">
            <label>Active?</label>
            <input type="hidden" name="active" value="0" />
            <input
                id="<?php echo $row_id; ?>-active"
                type="checkbox"
                name="active"
                value="1"
                <?php if ($row->active == 1): ?>checked="checked"<?php endif; ?>
                />
            <label for="<?php echo $row_id; ?>-active" class="checkbox-label<?php if ($row->active == 1): ?> checked<?php endif; ?>">Active</label>
            <?php echo form_error('active'); ?>
        </div>

        <div class="clear"></div>


                <div class="control-group">

                    <label>Groups</label>
                    <input type="hidden"  name="group_id" id="group_id" value="<?php echo $row->group_id ?>">
                    <div class="groups_tags_div tags_div">
                        <?php foreach($group_arr as $key => $value): ?>
                            <div>
                            <span>
                                <p id="<?php echo $value->id; ?>"><?php echo $value->name; ?>
                                    <span id="<?php echo $value->id; ?>" class="remove_group">x</span>
                                </p>
                            </span>
                            </div>
                        <?php endforeach; ?>
                        <input id="groups_search" type="text" placeholder="Search groups ..." value="" />
                    </div>
                    <div class="searched_groups searched_div"></div>

                </div>

                <div class="control-group">
                    <label>Facilitators</label>
                    <input type="hidden"  name="facilitator_id" id="facilitator_id" value="<?php echo $row->facilitator_id ?>">
                    <div class="facilitators_tags_div tags_div">
                        <?php foreach($facilitator_arr as $key => $value): ?>
                            <div>
                                <span>
                                    <p id="<?php echo $value->id; ?>"><?php echo $value->firstname.' '.$value->lastname; ?>
                                        <span id="<?php echo $value->id; ?>" class="remove_facilitator">x</span>
                                    </p>
                                </span>
                            </div>
                        <?php endforeach; ?>
                        <input id="facilitators_search" type="text" placeholder="Search facilitators ..." value="" />
                    </div>
                    <div class="searched_facilitators searched_div"></div>

                </div>

            <div class="control-group">
                <label>Users</label>
                <input type="hidden"  name="user_id" id="user_id" value="<?php echo $row->user_id ?>">
                <div class="users_tags_div tags_div">
                    <?php foreach($user_arr as $key => $value): ?>
                        <div>
                            <span>
                                <p id="<?php echo $value->id; ?>"><?php echo $value->firstname.' '.$value->lastname; ?>
                                    <span id="<?php echo $value->id; ?>" class="remove_user">x</span>
                                </p>
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <input id="users_search" type="text" placeholder="Search users ..." value="" />
                </div>
                <div class="searched_users searched_div"></div>
             </div>


        <div class="clear"></div>
        
        <?php
        date_default_timezone_set($this->client->time_zone);
        ?>
        
        <div class="control-group control-group-date-time">
            <label>Commencement date &amp; time</label>
            <input
                id="<?php echo $row_id; ?>-commence"
                class="mobile-date-picker time"
                type="text"
                name="commence"
                value="<?php echo set_value('commence', gmdate("Y-m-d\TH:i:s\Z", $row->commence)); ?>"
                data-value ="<?php echo gmdate("Y-m-d\TH:i:s\Z", $row->commence); ?>"
                data-min-date=""
                data-max-date="#<?php echo $row_id; ?>-expire"
                />
            <?php echo form_error('commence'); ?>
        </div>
        <div class="control-group control-group-date-time">
            <label>Expiry date &amp; time</label>
            <input
                id="<?php echo $row_id; ?>-expire"
                class="mobile-date-picker time"
                type="text"
                name="expire"
                value="<?php echo set_value('expire', gmdate("Y-m-d\TH:i:s\Z", $row->expire)); ?>"
                data-value ="<?php echo gmdate("Y-m-d\TH:i:s\Z", $row->expire); ?>"
                data-min-date="#<?php echo $row_id; ?>-commence"
                data-max-date=""
                />
            <?php echo form_error('expire'); ?>
        </div>
        
        <div class="clear"></div>
        
    </div>

    <div class="form-btns">
        <?php if ($action == 'add'): ?>
            <button type="submit" name="put" value="TRUE">Add</button>
            <a class="nav-btn cancel-add-btn">Cancel</a>
        <?php elseif ($action == 'edit'): ?>
            <button type="submit" name="update" value="TRUE">Update</button>
            <a class="nav-btn cancel-edit-btn">Cancel</a>
        <?php endif; ?>
        <div class="clear"></div>
    </div>

<?php echo form_close(); ?>