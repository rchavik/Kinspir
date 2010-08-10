<div id="top">
	<h3 class="title"><?php echo $this->Global->link('Kinspir <span>catchy tagline here</span>', '/home', array('escape' => false)); ?></h3>
	<div id="userbox">
		<div id="userSettings">
			Hello <?php echo $this->Global->link(User::get('name'), array('controller' => 'users', 'action' => 'edit')); ?>
			<?php if ($this->Session->check('Admin')) : ?>
				<strong>[ADMIN]</strong>
			<?php endif; ?>
			<ul>
				<li><?php echo $this->Global->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
				<li><?php echo $this->Global->link(__('My Account', true), array('controller' => 'users', 'action' => 'edit')); ?></li>
			</ul>
		</div>
		<p id="lastLogin">
		<?php if (!User::get('last_login')) : ?>
			Welcome to Kinspir!
		<?php else : ?>
			Last Login: <?php echo $this->Time->timeAgoInWords(User::get('last_login')); ?>
		<?php endif; ?>
		</p>
		<p>
			<?php echo $this->Global->link(__('Invites', true) . ' (' . User::get('invites') . ')', array('controller' => 'users', 'action' => 'invite'));?> |
			<?php echo $this->Global->link(__('Notifications', true) . ' (' . User::get('notification_count') . ')', array('controller' => 'notifications', 'action' => 'index')); ?>
		</p>
		<p>
			<?php echo $this->Global->link(__('Messages', true), array('controller' => 'messages', 'action' => 'index'));?> |
			<?php echo $this->Global->link(__('Connections', true), array('controller' => 'connections', 'action' => 'index'));?>
		</p>
	</div>
	<div class="right">
		<?php echo $this->Avatar->link($this->Session->read('Auth.User')); ?>
	</div>
	<?php echo $this->element('layout/breadcrumbs');?>
</div>
<?php echo $this->element('layout/menu'); ?>
<?php echo $this->element('layout/search'); ?>