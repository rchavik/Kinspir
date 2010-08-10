<div class="box">
	<h4>Connections</h4>
<?php if (empty($connections)) : ?>
	You do not have any connections
<?php else: ?>
	<ul class="feed">
		<?php foreach ($connections as $connection) : ?>
			<?php
				if ($connection['Receiver']['id'] == User::get('id')) {
					$user['id'] = $connection['User']['id'];
					$user['name'] = $connection['User']['name'];
					$user['email'] = $connection['User']['email'];
					$user['facebook_id'] = $connection['User']['facebook_id'];
					$user['username'] = $connection['User']['username'];
				} else {
					$user['id'] = $connection['Receiver']['id'];
					$user['name'] = $connection['Receiver']['name'];
					$user['email'] = $connection['Receiver']['email'];
					$user['facebook_id'] = $connection['Receiver']['facebook_id'];
					$user['username'] = $connection['Receiver']['username'];
				}
			?>
			<li>
				<?php echo $this->Avatar->link($user); ?>
				<div class="left">
					<?php echo $this->Html->link($user['name'], array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
					<?php if ($connection['Connection']['is_approved']) : ?>
						<br />
						<?php echo $user['email']; ?>
					<?php endif; ?>
					<?php if (!empty($user['username'])): ?>
						<br />
						<strong>Nickname:</strong> <?php echo $user['username']; ?>
					<?php endif; ?>
				</div>
				<div class="right">
				<?php 
					echo '<span>';
					if (!$connection['Connection']['is_approved'] && $connection['User']['id'] != User::get('id')) {
						echo $this->Html->link(__('[Approve]', true), array('action' => 'approve', $connection['Connection']['id']));
					} else if (!$connection['Connection']['is_approved'] && $connection['User']['id'] == User::get('id')) {
						__('Awaiting approval');
					} else {
						__('Connected');
					}
					echo '</span>';
					echo $this->Html->link(__(' [Delete Connection]', true), array('action' => 'delete', $connection['Connection']['id']));
				?>
				</div>
				<div class="clearfix"></div>
				<div style="clear:both"></div>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
</div>
<?php echo $this->element('layout/pagination')?>

<?php $this->Layout->blockStart('sidebar')?>
<div class="box">
	<h4><?php __('Connection Options');?></h4>
	<ul class="list-links">
		<li><?php echo $this->Javascript->link(__('List Connections', true), array('action'=>'index'), '#content-main') ?></li>
		<li><?php echo $this->Javascript->link(__('New Connection', true), array('action'=>'add'), '#content-main')?></li>
	</ul>
</div>
<?php if ($this->Session->check('FB.Me')) : ?>
	<div class="box">
		<h4>Facebook Friends</h4>
		<fb:friendpile max-rows="10"></fb:friendpile>
	</div>
<?php endif; ?>
<?php $this->Layout->blockEnd()?>
