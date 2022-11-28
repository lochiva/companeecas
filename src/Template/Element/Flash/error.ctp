<script>
$(document).ready(function(){
  /*setTimeout(function(){
    $('#error-message').hide("slow");
  }, 4000);*/
  $('#close-error-message').click(function() {
    $('#error-message').hide("slow");
  });
});
</script>
<div id="error-message" class="message error alert alert-danger">
  <span id="close-error-message">x</span>
  <?= h($message) ?>
</div>
