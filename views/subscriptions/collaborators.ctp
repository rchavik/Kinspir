<div class="box">
	<h4>Collaborators</h4>
	<ul class="user-avatars collaborators">
	<?php foreach ($subscribers as $subscriber): ?>
		<li>
			<div class="avatar">
				<?php echo $this->Avatar->link($subscriber['User'], null, array('size' => 85, 'class' => false)); ?>
				<?php echo $this->Html->link($subscriber['User']['name'], array('controller' => 'users', 'action' => 'view', $subscriber['User']['id'])); ?>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
</div>