<div class="tasks box">
	<h4>
	<?php echo $task['Task']['name']; ?>
		<div class="box-actions">
			<?php 
			echo $this->Html->link(null, array(
					'controller' => 'tasks', 
					'action' => 'edit', 
					$task['Task']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this task')); 
			?> 
			<?php 
			echo $this->Html->link(null, array(
					'controller' => 'tasks', 
					'action' => 'delete', 
					$task['Task']['id']), 
					array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this task'), 
					sprintf(__('Are you sure you want to delete "%s"?', true), 
					$task['Task']['name'])); 
			
			?>
		</div>
		<div style="clear:both"></div>
	</h4>
	
	<div>	
		<?php if (!empty($task['Task']['completed'])): ?>
			<div>
				<h3> <?php __('Completed'); ?> : </h3> 
				<?php echo ' ' . $this->Time->nice($task['Task']['completed']); ?>
			</div>
		<?php else: ?>
		<ul class="list-links">
			<li style="border:0">
		<?php echo $this->Html->link(__('Mark this task as complete', true), array('controller' => 'tasks', 'action' => 'complete', $task['Task']['id'])); ?>
			</li>
		</ul>
		<?php endif; ?>
		<hr/>
		
		<?php if (!empty($task['Task']['description'])): ?>
			<div>
				<h3> <?php __('Description'); ?> : </h3>
				<?php echo $task['Task']['description']; ?>
			</div>
			<br/>
		<?php endif; ?>
		
		<div>
			<h3>
				<?php __('Task Group'); ?> :
			</h3>
			<?php echo ' ' . $task['TaskGroup']['name']; ?>
		</div>
		
		<?php if (!empty($task['AssignedTo']['name'])): ?>
			<br/>
			<div>
				<h3><?php __('Assigned to');?> :</h3>
				<div>
					<?php echo $this->Avatar->link($task['AssignedTo']); ?><br/>
					<?php echo $this->Html->link($task['AssignedTo']['name'], array('controller' => 'users', 'action' => 'view', $task['AssignedTo']['id'])); ?>
				</div>
			</div>
		<?php endif; ?>
		<br/>
		<div>
			<h3> <?php __('Due'); ?> : </h3> 
			<?php echo ' ' . $this->Time->nice($task['Task']['due']); ?>
			<br/><br/>
			<h3> <?php __('Created'); ?> : </h3>
			<?php echo ' ' . $this->Time->nice($task['Task']['created']); ?>
			
			<?php if ($task['Task']['created'] != $task['Task']['updated']): ?>
				<br/><br/>
				<h3> <?php __('Last Updated'); ?> : </h3> 
				<?php echo ' ' . $this->Time->nice($task['Task']['updated']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php echo $this->element('layout/comments', array('comments' => $task['Comment'])); ?>