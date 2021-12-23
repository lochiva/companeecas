<script>
$(document).ready(function(){
  setTimeout(function(){
    $('#warning-message').hide("slow");
  }, 4000);
});
</script>
<div id="warning-message" class="message warning alert alert-warning"><?= h($message) ?></div>
