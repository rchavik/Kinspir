<div class="box">
	<h4>Newest Tasks</h4>
	<?php if (empty($tasks)):?>
	No tasks. Try adding some!
	<?php else:?>
	<ul class="todo-list">
	<?php foreach ($tasks as $task): ?>
		<?php if (($link = $this->Html->link($task['Task']['name'], array('controller'=>'tasks', 'action'=>'view', $task['Task']['id'])))):  ?>
		<li>
			<?php $checked = (!empty($task['Task']['is_complete']) ? 'X' : '&nbsp;'); ?>
			<?php echo $this->Html->link('[ ' . $checked . ' ]', array('controller' => 'tasks', 'action' => 'complete', $task['Task']['id']), array('title' => 'Mark this task as complete', 'escape' => false)); ?>
			<?php echo $link?> 
			<span>
				<?php echo $this->Time->timeAgoInWords($task['Task']['created']); ?>
				<?php if ($task['Task']['due']) echo 'Due: ' . $this->Time->niceShort($task['Task']['due'])?>
			</span>
		</li>
		<?php endif; ?>
	<?php endforeach;?>
	</ul>
	<?php endif;?>
</div>
