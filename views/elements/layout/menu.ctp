<ul id="menu">
	<li><?php echo $this->Global->link(__('Feed', true), array('controller' => 'feeds', 'action' => 'index'), array('current' => true));?></li>
	<?php if ($this->Session->check('Workspace')) : ?>
		<li><?php echo $this->Global->link(__('Tasks', true), array('controller' => 'tasks', 'action' => 'index'), array('current' => true));?></li>
		<li><?php echo $this->Global->link(__('Uploads', true), array('controller' => 'uploads', 'action' => 'index'), array('current' => true));?></li>
	<?php endif; ?>
	<li><?php echo $this->Global->link(__('Calendar', true), array('controller' => 'calendars', 'action' => 'index'), array('current' => true));?></li>
	<li><?php echo $this->Global->link(__('Repositories', true), array('controller' => 'projects', 'action' => 'index'), array('current' => true));?></li>
	<?php if (!$this->Session->check('Workspace')) : ?>
		<li><?php echo $this->Global->link(__('Getting Started', true), 'http://docs.kinspir.com/', array('current' => true, 'target' => '_blank'));?></li>
	<?php endif; ?>
</ul>