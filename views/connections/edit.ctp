<div class="connections form">
	<h4><?php __('Connection Request');?></h4>
	<?php echo $this->Form->create('Connection');?>
		<fieldset>
			<?php
				echo $this->Form->input('email');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
</div>