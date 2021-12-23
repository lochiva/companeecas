<script>
$(document).ready(function(){
  setTimeout(function(){
    $('#success-message').hide("slow");
  }, 4000);
});
</script>
<div id="success-message" class="message success alert alert-success"><?= h($message) ?></div>
