<script>
$(document).ready(function(){
  setTimeout(function(){
    $('#error-message').hide("slow");
  }, 4000);
});
</script>
<div id="error-message" class="message error alert alert-danger"><?= h($message) ?></div>
