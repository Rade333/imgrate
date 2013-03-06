<?php if ($message): ?>
  <div class="message <?php print $message->type; ?>">
  <div class="close">(click to close)</div>
    <?php print $message->text; ?>
  </div>
<?php endif; ?>
