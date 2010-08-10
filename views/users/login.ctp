<?php $this->set('title_for_layout', 'Login')?>
<?php echo $this->Form->create('User'); ?>
	<div>
		<?php echo $this->Form->input('email', array('label' => 'Email:')); ?>
		<?php echo $this->Form->input('password', array('label' => 'Password:')); ?>
	</div>
	<div>
		<?php echo $this->Form->input('remember_me', array('type' => 'checkbox', 'checked' => 'checked'));  ?>
		<?php echo $this->Form->submit('login')?> 
		<?php echo $this->Html->link('Forgot your Password?', array('controller' => 'users', 'action' => 'reset'))?>
	</div>
<?php echo $this->Form->end(); ?>