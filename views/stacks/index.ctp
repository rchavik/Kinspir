<div class="box">
	<h4>Stacks</h4>
	<ul class="item-list">
		<?php foreach ($stacks as $stack): ?>
			<li id="stack_<?php echo $stack['Stack']['id']; ?>" class="with-link-actions">
				<strong><?php echo $this->Html->link($stack['Stack']['name'], array('controller' => 'stacks', 'action' => 'view', $stack['Stack']['id'])); ?></strong>
				<?php if (!empty($stack['Stack']['comment_count'])) : ?>
					<span class="ui-icon ui-icon-comment"></span>
				<?php endif; ?>
				<div class="hidden-link-actions right">
					<?php if (!empty($stack['Stack']['description'])) : ?>
						<?php echo $this->Javascript->toggle(null, array('title' => 'Show Description','class' => 'ui-icon ui-icon-folder-open', 'update' => '#stack-description' . $stack['Stack']['id'], 'div' => false));?>
					<?php endif; ?>
					<?php echo $this->Html->link(null, array('controller' => 'stacks', 'action' => 'edit', $stack['Stack']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this stack')); ?>
					<?php echo $this->Html->link(null, array('controller' => 'stacks', 'action' => 'delete', $stack['Stack']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this stack'), sprintf(__('Are you sure you want to delete %s?', true), $stack['Stack']['name'])); ?>
				</div>
				<?php if (!empty($stack['Stack']['description'])) : ?>
					<div id="stack-description<?php echo $stack['Stack']['id']; ?>" style="display: none;" class="item-description">
						<?php echo $stack['Stack']['description']; ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php if ($this->Session->check('Workspace')) : ?>
	<?php echo $this->Javascript->toggle('New Stack', array('url' => array('controller' => 'stacks', 'action' => 'add'), 'class' => 'button'));?>
<?php endif; ?>

<?php echo $this->element('layout/pagination'); ?>