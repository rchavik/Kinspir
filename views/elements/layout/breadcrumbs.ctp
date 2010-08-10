<h3 id="breadcrumbs">
	<div>
		<?php echo $this->Global->link('Home', '/home', array('current' => true)); ?>
	</div>
	<?php
		if ($this->Session->check('Workspace')) {
			echo '&raquo;';
		} else {
			echo '|';
		}
	?>
	<div>
		<?php
			if ($this->Session->check('Workspace')) {
				$workspaceName = $this->Session->read('Workspace.name');
				if (strlen($workspaceName) >= 36) {
					$workspaceName = substr($workspaceName, 0, 36);
					$workspaceName .= '...';
				}
				echo $this->Global->link($workspaceName, array('controller' => 'workspaces', 'action' => 'view', $this->Session->read('Workspace.id')), array('current' => true));
			} else {
				echo $this->Global->link('Workspaces', array('controller' => 'workspaces', 'action' => 'index'), array('class' => 'inactive', 'current' => true));
			}
		?>
		<ul>
			<?php if ($this->Session->check('Workspace')): ?>
				<li class="first">
					<?php echo $this->Global->link('Workspaces', array('controller' => 'workspaces', 'action' => 'view'))?>
				</li>
			<?php endif;?>
			<?php foreach($this->Session->read('Workspaces') as $id => $name):?>
				<?php 
					if (strlen($name) >= 24) {
						$name = substr($name, 0, 24);
						$name .= '...';	
					}
				?>
				<li<?php if ($id == $this->Session->read('Workspace.id')) echo ' class="current"'?>>
					<?php echo $this->Global->link($name, array('controller' => 'workspaces', 'action' => 'view', $id))?>
				</li>
			<?php endforeach;?>
			<li>
				<?php echo $this->Global->link('New Workspace...', array('controller' => 'workspaces', 'action' => 'add'), array('class' => 'inactive')); ?>
			</li>
		</ul>
	</div>
	<?php
		if ($this->Session->check('Milestone')) {
			echo '&raquo;';
		} elseif (!$this->Session->check('Stack')) {
			echo '|';
		}
	?>
	<?php if (($this->Session->check('Milestone') && $this->Session->check('Stack')) || !$this->Session->check('Stack')) : ?>
		<div>
			<?php
				if ($this->Session->check('Milestone')) {
					echo $this->Global->link($this->Session->read('Milestone.name'), array('controller' => 'milestones', 'action' => 'view', $this->Session->read('Milestone.id')), array('current' => true));
				} else {
					echo $this->Global->link('Milestones', array('controller' => 'milestones', 'action' => 'index'), array('class' => 'inactive', 'current' => true));
				}
			?>
			<?php if ($this->Session->read('Milestones')) : ?>
				<ul>
					<?php if ($this->Session->check('Milestone')):?>
						<li class="first">
							<?php echo $this->Global->link('Milestones', array('controller' => 'milestones', 'action' => 'index'))?>
						</li>
					<?php endif;?>
						<?php foreach($this->Session->read('Milestones') as $id => $name):?>
							<li<?php if ($id == $this->Session->read('Milestone.id')) echo ' class="current"'?>>
								<?php echo $this->Global->link($name, array('controller' => 'milestones', 'action' => 'view', $id))?>
							</li>
						<?php endforeach;?>
					<li>
						<?php echo $this->Global->link('New Milestone...', array('controller' => 'milestones', 'action' => 'add'), array('class' => 'inactive')); ?>
					</li>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
		if ($this->Session->check('Stack')) {
			echo '&raquo;';
		} else {
			echo '|';
		}
	?>
	<div>
		<?php
			if ($this->Session->check('Stack')) {
				echo $this->Global->link($this->Session->read('Stack.name'), array('controller' => 'stacks', 'action' => 'view', $this->Session->read('Stack.id')), array('current' => true));
			} else {
				echo $this->Global->link('Stacks', array('controller' => 'stacks', 'action' => 'index'), array('class' => 'inactive', 'current' => true));
			}
		?>
		<?php if ($this->Session->read('Stacks')) : ?>
			<ul>
				<?php if ($this->Session->check('Stack')):?>
					<li class="first">
						<?php echo $this->Global->link('Stacks', array('controller' => 'stacks', 'action' => 'index'))?>
					</li>
				<?php endif;?>
					<?php foreach($this->Session->read('Stacks') as $id => $name):?>
						<li<?php if ($id == $this->Session->read('Stack.id')) echo ' class="current"'?>>
							<?php echo $this->Global->link($name, array('controller' => 'stacks', 'action' => 'view', $id))?>
						</li>
					<?php endforeach;?>
				<li>
					<?php echo $this->Global->link('New Stack...', array('controller' => 'stacks', 'action' => 'add'), array('class' => 'inactive')); ?>
				</li>
			</ul>
		<?php endif; ?>
	</div>
</h3>