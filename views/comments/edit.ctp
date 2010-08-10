<div class="comments form">
	<h4><?php printf(__('Add %s', true), __('Comment', true)); ?></h4>
	<?php echo $this->Form->create('Comment');?>
	<fieldset>
		<?php
			echo $this->Form->input('body');
			echo $this->Form->hidden('class');
			echo $this->Form->hidden('foreign_id');
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>