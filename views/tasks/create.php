<?php include views_path('layouts/header.php'); ?>

<?php include views_path('layouts/sidebar.php'); ?>

<?php include views_path('partials/alerts.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Create Task</h1>
            <form action="<?= url('tasks') ?>" method="POST">

                <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                <div class="form-group mt-3">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= old('title') ?>">
                    <?php if (has_error('title')) : ?>
                        <small class="text-danger"><?= get_error('title') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description"
                              class="form-control"><?= old('description') ?></textarea>
                    <?php if (has_error('description')) : ?>
                        <small class="text-danger"><?= get_error('description') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="low" <?= old('priority') === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= old('priority') === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= old('priority') === 'high' ? 'selected' : '' ?>>High</option>
                    </select>
                    <?php if (has_error('priority')) : ?>
                        <small class="text-danger"><?= get_error('priority') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control"
                           value="<?= old('due_date') ?>">
                    <?php if (has_error('due_date')) : ?>
                        <small class="text-danger"><?= get_error('due_date') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="assigned_to">Assigned To</label>
                    <select name="assigned_to" id="assigned_to" class="form-control">
                        <option value="" disabled>Select Employee</option>
                        <?php if (isset($employees) && is_array($employees) && count($employees) > 0): ?>
                            <?php foreach ($employees as $employee) : ?>
                                <option value="<?= $employee->id ?>" <?= old('assigned_to') == $employee->id ? 'selected' : '' ?>><?= $employee->full_name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (has_error('assigned_to')) : ?>
                        <small class="text-danger"><?= get_error('assigned_to') ?></small>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Create Task</button>

            </form>
        </div>
    </div>
</div>

<?php include views_path('layouts/footer.php'); ?>
