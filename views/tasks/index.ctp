<?php if (!empty($taskGroups)) : ?>
	<?php if ($this->Session->check('Workspace.id')) :?>
		<script type="text/javascript">
			$(document).ready(function () {
				<?php foreach ($taskGroups as $taskGroup): ?>
					$("#task-group-tasks-<?php echo $taskGroup['TaskGroup']['id']; ?>").sortable({
						containment:"#task-groups",
						connectWith: '.taskGroup',
						axis: 'y',
						update: function(){updateOrder("tasks", <?php echo $taskGroup['TaskGroup']['id']; ?>);}
					});
				<?php endforeach; ?>
			});
		</script>
	<?php endif; ?>
	
	<div id="task-groups">
		<?php foreach ($taskGroups as $taskGroup): ?>
			<div class="box" id="taskgroup_<?php echo $taskGroup['TaskGroup']['id']; ?>">
				<h4 class="movable"><?php echo $this->Html->link($taskGroup['TaskGroup']['name'], array('controller' => 'tasks', 'action' => 'index', 'task-group' => $taskGroup['TaskGroup']['id'])); ?><div class="box-actions"><?php echo $this->Html->link(null, array('controller' => 'task_groups', 'action' => 'edit', $taskGroup['TaskGroup']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this task group')); ?> <?php echo $this->Html->link(null, array('controller' => 'task_groups', 'action' => 'delete', $taskGroup['TaskGroup']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this task group'), sprintf(__('Are you sure you want to delete %s?', true), $taskGroup['TaskGroup']['name'])); ?></div></h4>
				<?php if(!empty($taskGroup['TaskGroup']['description'])) : ?>
					<div class="task-group-description">
						<?php echo $taskGroup['TaskGroup']['description']; ?>
					</div>
				<?php endif; ?>
				<ul id="task-group-tasks-<?php echo $taskGroup['TaskGroup']['id']; ?>" class="order-list taskGroup">
					<?php if (!empty($taskGroup['Task'])) :?>
						<?php foreach ($taskGroup['Task'] as $task): ?>
							<li id="task_<?php echo $task['id']; ?>" class="with-link-actions movable">
								<div class="handle">&nbsp;</div>
								<?php if (!empty($task['comment_count'])) : ?>
									<div class="comment-icon">
										<?php echo $this->Javascript->toggle(null, array('url' => array('controller' => 'comments', 'action' => 'add', 'Task' => $task['id']), 'class' => 'ui-icon ui-icon-comment', 'title' => 'Comment', 'update' => '#comment-task-panel' . $task['id'], 'div' => false)); ?>
									</div>
								<?php endif; ?>
								<div class="hidden-link-actions right">
									<?php if (!empty($task['description'])) : ?>
										<?php echo $this->Javascript->toggle(null, array('title' => 'Show Description', 'class' => 'ui-icon ui-icon-folder-open', 'update' => '#task-description' . $task['id'], 'div' => false));?>
									<?php endif; ?>
									<?php echo $this->Javascript->toggle(null, array('url' => array('controller' => 'tasks', 'action' => 'edit', $task['id']), 'class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this task', 'update' => '#edit-task-panel' . $task['id'], 'div' => false)); ?>
									<?php echo $this->Html->link(null, array('controller' => 'tasks', 'action' => 'delete', $task['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this task'), sprintf(__('Are you sure you want to delete %s?', true), $task['name'])); ?>
								</div>
								<?php echo $this->Html->link('[ &nbsp; ]', array('controller' => 'tasks', 'action' => 'complete', $task['id']), array('title' => 'Mark this task as complete', 'escape' => false)); ?>
								<strong><?php echo $this->Html->link($task['name'], array('controller' => 'tasks', 'action' => 'view', $task['id'])); ?></strong>
								<?php if (!empty($task['AssignedTo'])) : ?>
									<div class="assigned-to">
										Assigned to <?php echo $this->Html->link($task['AssignedTo']['name'], array('controller' => 'users', 'action' => 'view', $task['AssignedTo']['id'])); ?>
									</div>
								<?php endif; ?>
								<?php if (!empty($task['Stack'])) : ?>
									<div class="assigned-to">
										Stack: <?php echo $this->Html->link($task['Stack']['name'], array('controller' => 'stacks', 'action' => 'view', $task['Stack']['id'])); ?>
									</div>
								<?php endif; ?>
								<?php if (!empty($task['description'])) : ?>
									<div id="task-description<?php echo $task['id']; ?>" class="item-description">
										<?php echo $task['description']; ?>
									</div>
								<?php endif; ?>
								<div id="edit-task-panel<?php echo $task['id']; ?>" class="loading"></div>
								<div id="comment-task-panel<?php echo $task['id']; ?>" class="loading"></div>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				<?php if ($this->Session->check('Workspace.id') && empty($this->params['named']['task-group'])):?>
					<?php echo $this->Javascript->toggle('New Task', array('url' => array('controller' => 'tasks', 'action' => 'add', 'task-group' => $taskGroup['TaskGroup']['id']), 'class' => 'right button', 'update' => 'new-task-panel' . $taskGroup['TaskGroup']['id']));?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	
	<?php if ($this->Session->check('Workspace.id') && empty($this->params['named']['task-group'])):?>
		<?php echo $this->Javascript->toggle('New Task Group', array('url' => array('controller' => 'task_groups', 'action' => 'add'), 'class' => 'button'));?>
	<?php endif; ?>
	
	<?php $this->Layout->blockStart('sidebar')?>
	<div class="box">
		<h4><?php __('Task Groups');?></h4>
		<ul class="list-links">
			<?php foreach ($taskGroups as $taskGroup) : ?>
				<li><?php echo $this->Html->link($taskGroup['TaskGroup']['name'], '#taskgroup_' . $taskGroup['TaskGroup']['id']); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php $this->Layout->blockEnd()?>
<?php endif; ?>

<?php if (!empty($tasks)) : ?>
	<div class="box">
		<h4>Tasks</h4>
		<ul class="order-list taskGroup">
			<?php foreach ($tasks as $task): ?>
				<li id="task_<?php echo $task['Task']['id']; ?>" class="with-link-actions">
					<?php if (!empty($task['Task']['comment_count'])) : ?>
						<div class="comment-icon">
							<?php echo $this->Javascript->toggle(null, array('url' => array('controller' => 'comments', 'action' => 'add', 'Task' => $task['Task']['id']), 'class' => 'ui-icon ui-icon-comment', 'title' => 'Comment', 'update' => '#comment-task-panel' . $task['Task']['id'], 'div' => false)); ?>
						</div>
					<?php endif; ?>
					<div class="hidden-link-actions right">
						<?php if (!empty($task['Task']['description'])) : ?>
							<?php echo $this->Javascript->toggle(null, array('title' => 'Show Description', 'class' => 'ui-icon ui-icon-folder-open', 'update' => '#task-description' . $task['Task']['id'], 'div' => false));?>
						<?php endif; ?>
						<?php echo $this->Javascript->toggle(null, array('url' => array('controller' => 'tasks', 'action' => 'edit', $task['Task']['id']), 'class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this task', 'update' => '#edit-task-panel' . $task['Task']['id'], 'div' => false)); ?>
						<?php echo $this->Html->link(null, array('controller' => 'tasks', 'action' => 'delete', $task['Task']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this task'), sprintf(__('Are you sure you want to delete %s?', true), $task['Task']['name'])); ?>
					</div>
					<?php echo $this->Html->link('[ &nbsp; ]', array('controller' => 'tasks', 'action' => 'complete', $task['Task']['id']), array('title' => 'Mark this task as complete', 'escape' => false)); ?>
					<strong><?php echo $this->Html->link($task['Task']['name'], array('controller' => 'tasks', 'action' => 'view', $task['Task']['id'])); ?></strong>
					<?php if (!empty($task['Task']['description'])) : ?>
						<div id="task-description<?php echo $task['Task']['id']; ?>" class="item-description">
							<?php echo $task['Task']['description']; ?>
						</div>
					<?php endif; ?>
					<div id="edit-task-panel<?php echo $task['Task']['id']; ?>" class="loading"></div>
					<div id="comment-task-panel<?php echo $task['Task']['id']; ?>" class="loading"></div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php echo $this->element('layout/pagination')?>