<div class="box">
	<h4>User</h4>
	<?php if (empty($user)) : ?>
		No recent activity
	<?php else: ?>
		<ul class="feed">
				<li>
					<?php echo $this->Avatar->link($user['User']); ?>
					<?php
						if ($user['User']['name'] === User::get('name')) {
							$username = 'You';
						} else {
							$username = $user['User']['name'];
						}
						echo $this->Html->link($username, array('controller' => 'users', 'action' => 'view', $user['User']['id'])); 
					?>
					<span>Joined <?php echo $this->Time->timeAgoInWords($user['User']['created'])?></span>
				</li>
		</ul>
	<?php endif; ?>
</div>

<div id="user-feed" class="loading box" style="display: block">
	<h4>Loading Feed...</h4>
	<?php echo $this->Javascript->load(array('controller' => 'feeds', 'action' => 'index', 'user' => $user['User']['id']), '#user-feed', array('replace' => true));?>
</div>

<?php $this->Layout->blockStart('sidebar')?>
<div class="box">
	<h4><?php __('Users Menu');?></h4>
	<ul class="list-links">
		<li><?php echo $this->Javascript->link(__('Message this User', true), array('controller' => 'messages', 'action' => 'add'))?></li>
		<?php 
			$already_connected_to = false;
//			foreach ($connections as $connection) {
//				if ($connection['User']['id'] == $user['User']['id']) {
//					$already_connected_to = true;
//					break;
//				}
//			}
			if (!$already_connected_to) {
				//echo '<li>' . $this->Javascript->link(__('Connect with this User', true), array('controller' => 'connections', 'action' => 'add'), '#content-main') . '</li>';	
		?>
		<?php 
			}
		?>
	</ul>
</div>
<?php $this->Layout->blockEnd()?>