<div class="box">
	<h4><?php __('Compose Message');?></h4>
	<div class="messages form">
	<?php echo $this->Form->create('Message');?>
		<fieldset>
			<div class="input text fcbk">
			      <label><?php __('Recipients');?>:</label>
			      <div id="fcbk" class="fcbk">
				      <select name="data[Recipients]" id="Recipients">
				      </select>
			      </div>
				<?php if (!empty($subscribers)) :?>
					<div class="input">
						<strong><?php __('Current participants');?>: &nbsp;&nbsp;&nbsp;</strong><?php echo implode('; ', $subscribers); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php
			if (isset($parent_id)) {
				echo $this->Form->hidden('parent_id', array('value'=>$parent_id));
			}
			echo $this->Form->input('title');
			echo $this->Form->input('body');
		?>
		</fieldset>
	<?php echo $this->Form->end('Submit')?>
	</div>
</div>