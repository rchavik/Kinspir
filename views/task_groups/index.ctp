<?php if (!empty($taskGroups)) : ?>
	<h2>Task Groups</h2>
<?php endif; ?>
<?php foreach ($taskGroups as $taskGroup): ?>
	<div class="box" id="taskGroup-box-<?php echo $taskGroup['TaskGroup']['id']; ?>">
		<h4>
			<?php echo $this->Html->link($taskGroup['TaskGroup']['name'], array('controller' => 'taskGroups', 'action' => 'view', $taskGroup['TaskGroup']['id']));?>
			<div class="box-actions">
				<?php echo $this->Html->link(null, array('controller' => 'taskGroups', 'action' => 'edit', $taskGroup['TaskGroup']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this taskGroup')); ?> <?php echo $this->Html->link(null, array('controller' => 'taskGroups', 'action' => 'delete', $taskGroup['TaskGroup']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this taskGroup'), sprintf(__('Are you sure you want to delete %s?', true), $taskGroup['TaskGroup']['name'])); ?> 
			</div>
		</h4>
		<?php if (!empty($taskGroup['TaskGroup']['description'])) : ?>
			<?php echo $taskGroup['TaskGroup']['description']; ?>
		<?php endif; ?>
	</div>
<?php endforeach; ?>

<?php if (!empty($taskGroups)) : ?>
	<?php echo $this->element('layout/pagination')?>
<?php endif; ?>