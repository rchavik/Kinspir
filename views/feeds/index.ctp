<div class="box">
	<h4>
		Feed
		<?php
			if (!empty($this->params['named']['user']) && $this->Session->check('Workspace.id') && empty($this->params['named']['view']) || (!empty($this->params['named']['view']) && $this->params['named']['view'] != 'combined')) {
				echo ' : ' . $this->Html->link('View Combined Feed', array('controller' => 'feeds', 'action' => 'index', 'user' => $this->params['named']['user'], 'view' => 'combined'));
			}
		?></h4>
<?php if (empty($feeds)) : ?>
	No recent activity
<?php else: ?>
	<ul class="feed">
		<?php foreach ($feeds as $feed) : ?>
			<li><?php
				echo $this->Avatar->link($feed['User']);
				if ($feed['User']['name'] === User::get('name')) {
					$username = 'You';
				} else {
					$username = $feed['User']['name'];
				}
				$stack = null;
				$milestone = null;
				$workspace = null;
				if (!empty($feed['Feed']['stack_id']) && $this->Session->check('Workspace.id') && !$this->Session->check('Stack.id') && $feed['Feed']['class'] !== 'Stack') {
					$stack = ' under the Stack ' . $this->Html->link($feed['Stack']['name'], array('controller' => 'stacks', 'action' => 'view', $feed['Stack']['id']));
				} elseif (empty($feed['Feed']['stack_id']) && !empty($feed['Feed']['milestone_id']) && $this->Session->check('Workspace.id') && !$this->Session->check('Milestone.id') && $feed['Feed']['class'] !== 'Milestone') {
					$milestone = ' under the Milestone ' . $this->Html->link($feed['Milestone']['name'], array('controller' => 'milestones', 'action' => 'view', $feed['Milestone']['id']));
				} elseif (!empty($feed['Feed']['workspace_id']) && !$this->Session->check('Workspace.id') && $feed['Feed']['class'] !== 'Workspace') {
					$workspace = ' under ' . $this->Html->link($feed['Workspace']['name'], array('controller' => 'workspaces', 'action' => 'view', $feed['Workspace']['id']));
				}
				echo $this->Tools->insertVars(
					Configure::read('FeedMessages.' . $feed['Feed']['feed_action']),
					array(
						$this->Html->link($username, array('controller' => 'users', 'action' => 'view', $feed['User']['id'])),
						Inflector::humanize(Inflector::underscore($feed['Feed']['class'])),
						$this->Html->link($feed[$feed['Feed']['class']]['display_field'], array('controller' => Inflector::underscore(Inflector::tableize($feed['Feed']['class'])), 'action' => 'view', $feed[$feed['Feed']['class']]['id']), array('class' => 'bold')) . $workspace . $milestone . $stack,
						'<span>' . $time->timeAgoInWords($feed['Feed']['created']) . '</span>'
					)
				); 
			?></li>
		<?php endforeach; ?>
	</ul>		
<?php endif; ?>
</div>
<?php echo $this->element('layout/pagination')?>