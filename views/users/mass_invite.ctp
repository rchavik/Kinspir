<div class="users form">
	<h4><?php __('Invite People'); ?></h4>
	<?php echo $this->Form->create('User', array('action' => 'mass_invite'));?>
			<fieldset>
				<?php
					echo $this->Form->textarea('users');
				?>
			</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>