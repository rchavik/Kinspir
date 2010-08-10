<div class="box">
	<h4><?php echo $this->params['project']; ?></h4>
	<ul class="list-links">
		<li><?php
			$options = ($this->name == 'Timeline') ? array('class' => 'on') : null;
			echo $html->link(__('Timeline',true), array('admin' => false, 'controller' => 'timeline', 'action' => 'index'));
		?></li>
		<li><?php
			$options = ($this->name == 'Commits') ? array('class' => 'on') : null;
			echo $html->link(__('Commits',true), array('admin' => false, 'controller' => 'commits', 'action' => 'index'));
		?></li>
		<li><?php
			$options = ($this->name == 'Source') ? array('class' => 'on') : null;
			echo $html->link(__('Source',true), array('admin' => false, 'controller' => 'source', 'action' => 'index'));
		?></li>
		<?php if (!empty($this->params['isAdmin'])) : ?>
			<li><?php
				$options = ($this->name == 'Permissions') ? array('class' => 'on') : null;
				echo $html->link(__('Permissions',true), array('admin' => false, 'controller' => 'project_permissions', 'action' => 'index'));
			?></li>
			<li><?php
				$options = ($this->name == 'Users') ? array('class' => 'on') : null;
				echo $html->link(__('Users',true), array('admin' => false, 'controller' => 'users', 'action' => 'index'));
			?></li>
			<li><?php
				echo $html->link(__('Settings',true), array('admin' => false, 'controller' => 'projects', 'action' => 'edit'))
			?></li>
			<li><?php
				echo $html->link(__('Delete',true), array('admin' => false, 'controller' => 'projects', 'action' => 'delete'))
			?></li>
		<?php endif; ?>
	</ul>
</div>