<div class="users form">
	<h4><?php __('Invite Someone'); ?></h4>
	<?php echo $this->Form->create('User', array('action' => 'invite'));?>
			<fieldset>
				<?php
					echo $this->Form->input('name');
					echo $this->Form->input('email');
				?>
			</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>