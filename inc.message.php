<?php if ($message): ?>
  <div class="alert <?php print $message->type; ?> fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php print $message->text; ?>
  </div>
<?php endif; ?>
