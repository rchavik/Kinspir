<?php if (isset($sidebar_for_layout)) echo $sidebar_for_layout;?>
	<?php if (!empty($this->params['project'])) {
		echo $this->element('project_administration');
	} ?>
	<?php if ($this->Session->check('Workspace')): ?>
		<div id="newest-tasks" class="loading box" style="display: block">
			<h4>Loading Newest Tasks...</h4>
			<?php echo $this->Javascript->load(array('controller' => 'tasks', 'action' => 'newest'), '#newest-tasks', array('replace' => true));?>
		</div>
	<?php endif;?>
	<div id="calendar" class="loading box" style="display: block">
		<h4>Loading Calendar...</h4>
		<?php echo $this->Javascript->load(array('controller' => 'calendars', 'action' => 'widget'), '#calendar', array('replace' => true));?>
	</div>
	<?php if ($this->Session->read('Workspace')):?>
		<div id="collaborators" class="loading box" style="display: block">
			<h4>Loading Collaborators...</h4>
			<?php echo $this->Javascript->load(array('controller' => 'subscriptions', 'action' => 'collaborators'), '#collaborators', array('replace' => true));?>
		</div>
	<?php endif;?>
