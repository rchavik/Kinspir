<div class="wikiPages form">
	<h4><?php printf(__('Edit %s', true), __('Wiki Page', true)); ?></h4>
	<?php echo $this->Form->create('WikiPage');?>
		<fieldset>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->hidden('workspace_id');
				echo $this->Form->input('title');
				echo $this->Form->input('content', array('class' => 'markitup-editor'));
				if (!$this->Session->check('Stack')) {
					echo $this->Form->input('stack_id', array('empty' => 'None', 'class' => 'hidden', 
						'label' => $this->Javascript->toggle('Move To Stack', array('update' => '#WikiPageStackId', 'div' => false))
					));
				}
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<script>
$(".markitup-editor").markItUp(mySettings);
</script>