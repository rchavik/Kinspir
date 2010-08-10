<?php $this->set('title_for_layout', 'Reset Account')?>
<?php echo $this->Form->create('User'); ?>
	<?php echo $form->input('id'); ?>
	<div>
		<?php echo $this->Form->input('email', array('label' => 'Email:', 'class'=>'text')); ?>
		<?php echo $this->Form->submit('submit')?>
	</div>
	<div><?php echo $this->Html->link('Return to Login', array('controller' => 'users', 'action' => 'login'))?></div>
<?php echo $this->Form->end(); ?>