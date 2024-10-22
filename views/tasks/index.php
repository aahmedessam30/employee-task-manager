<?php include views_path('layouts/header.php'); ?>

<?php include views_path('layouts/sidebar.php'); ?>

<?php include views_path('partials/alerts.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Tasks</h1>
            <a href="<?= url('tasks/create') ?>" class="btn btn-primary">Create Task</a>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($tasks) && is_array($tasks->items()) && count($tasks->items()) > 0): ?>
                        <?php $loop = 1; ?>
                        <?php foreach ($tasks->items() as $task) : ?>
                            <tr>
                                <th scope="row"><?= $loop ?></th>
                                <td><?= $task->title ?></td>
                                <td><?= $task->description ?></td>
                                <td><?= $task->status ?></td>
                                <td><?= $task->priority ?></td>
                                <td><?= $task->due_date ?></td>
                                <td>
                                    <a href="<?= url('tasks/' . $task->id . '/edit') ?>" class="btn btn-warning">Edit</a>
                                    <form action="<?= url('tasks/' . $task->id) ?>" method="POST" style="display: inline-block">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php $loop++; endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No tasks found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php if (isset($tasks) && is_array($tasks) && count($tasks) > 0): ?>
                <?= $tasks->links() ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include views_path('layouts/footer.php'); ?>
