<div id="stack-feed" class="loading box" style="display: block">
	<h4>Loading Feed...</h4>
	<?php echo $this->Javascript->load(array('controller' => 'feeds', 'action' => 'index'), '#stack-feed', array('replace' => true));?>
</div>

<div id="stack-task-groups" class="loading box" style="display: block">
	<h4>Loading Feed...</h4>
	<?php echo $this->Javascript->load(array('controller' => 'task_groups', 'action' => 'index'), '#stack-task-groups', array('replace' => true));?>
</div>

<div id="stack-tasks" class="loading box" style="display: block">
	<h4>Loading Feed...</h4>
	<?php echo $this->Javascript->load(array('controller' => 'tasks', 'action' => 'index', 'stack' => $this->Session->read('Stack.id')), '#stack-tasks', array('replace' => true));?>
</div>