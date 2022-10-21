<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Surveys Interviews Status'), ['action' => 'edit', $surveysInterviewsStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Surveys Interviews Status'), ['action' => 'delete', $surveysInterviewsStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $surveysInterviewsStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Surveys Interviews Statuses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Surveys Interviews Status'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="surveysInterviewsStatuses view large-9 medium-8 columns content">
    <h3><?= h($surveysInterviewsStatus->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($surveysInterviewsStatus->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($surveysInterviewsStatus->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($surveysInterviewsStatus->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($surveysInterviewsStatus->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($surveysInterviewsStatus->modified) ?></td>
        </tr>
    </table>
</div>
