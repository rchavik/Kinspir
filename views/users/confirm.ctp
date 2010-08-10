<?php $this->set('title_for_layout', 'Confirm Account')?>
<?php echo $this->Form->create('User', array('action' => 'confirm')); ?>
	<?php echo $form->input('id'); ?>
	<div>
		<?php echo $this->Form->input('new_password', array('type' => 'password', 'label' => 'New Password:')); ?>
	</div>
	<div>
		<?php echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => 'Confirm Password:')); ?>
	</div>
<?php echo $this->Form->end('submit'); ?>