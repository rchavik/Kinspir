<div class="events view">
	<dl>
		<dt><?php echo $event['Event']['name']; ?></dt>
		<?php if (!empty($task['Event']['description'])) : ?>
			<dd>
				<?php echo $event['Event']['description']; ?>
				&nbsp;
			</dd>
		<?php endif; ?>
		<dt><?php __('Starts'); ?></dt>
		<dd>
			<?php echo $this->Time->nice($event['Event']['starts']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Ends'); ?></dt>
		<dd>
			<?php echo $this->Time->nice($event['Event']['ends']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Created'); ?></dt>
		<dd>
			<?php echo $this->Time->nice($event['Event']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Updated'); ?></dt>
		<dd>
			<?php echo $this->Time->nice($event['Event']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
