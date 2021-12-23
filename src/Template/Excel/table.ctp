<h3 class="box-title text-center">
  <?php if (!empty($header)): ?>
    <?= h($header) ?>
  <?php else: ?>
    <?= h($title) ?>
  <?php endif ?>
</h3>
<div class="box-body no-padding">
  <table class="table table-striped">
    <thead>
      <tr>
        <?php foreach ($columns as $key => $value): ?>
          <th><?= h($key) ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $dataSet): ?>
        <tr>
          <?php foreach ($dataSet as $key => $value): ?>
            <td><?= h($value) ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php if (!empty($landscape)): ?>
    <style>
    @page { size: landscape; }
    </style>
<?php endif; ?>
<script>
window.print();
</script>
