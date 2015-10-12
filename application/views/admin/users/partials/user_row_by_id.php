<?php foreach ($users as $row): ?>
    <li class="data-row">
        <?php echo $this->load->view('admin/users/partials/user_row', array(
            'row'					=> $row,
        ), TRUE); ?>
    </li>
<?php endforeach; ?>