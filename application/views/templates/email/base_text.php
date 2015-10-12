<?php echo $message; ?>

<?php if (is_object($client)): ?><?php echo $client->name; ?> Notification<?php endif; ?>

<?php if (is_object($client)): ?><?php echo $client->name; ?> powered by <?php endif; ?><?php echo APPLICATION_NAME; ?>


