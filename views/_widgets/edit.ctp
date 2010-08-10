<div class="widgets form">
	<h4><?php echo (!empty($this->data['Widget']['name'])) ? $this->data['Widget']['name'] : 'New'; ?> <?php __('Widget');?></h4>
<?php echo $this->Form->create('Widget');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
		echo $this->Form->input('params');
		//echo $this->Form->input('plugin');
		//echo $this->Form->input('link_type');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>