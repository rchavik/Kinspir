<div class="box">
	<h4>Milestones</h4>
	<ul class="item-list">
		<?php foreach ($milestones as $milestone): ?>
			<li id="milestone_<?php echo $milestone['Milestone']['id']; ?>" class="with-link-actions">
				<strong><?php echo $this->Html->link($milestone['Milestone']['name'], array('controller' => 'milestones', 'action' => 'view', $milestone['Milestone']['id'])); ?></strong>
				<?php if (!empty($milestone['Milestone']['comment_count'])) : ?>
					<span class="ui-icon ui-icon-comment"></span>
				<?php endif; ?>
				<div class="hidden-link-actions right">
					<?php if (!empty($milestone['Milestone']['description'])) : ?>
						<?php echo $this->Javascript->toggle(null, array('title' => 'Show Description','class' => 'ui-icon ui-icon-folder-open', 'update' => '#milestone-description' . $milestone['Milestone']['id'], 'div' => false));?>
					<?php endif; ?>
					<?php echo $this->Html->link(null, array('controller' => 'milestones', 'action' => 'edit', $milestone['Milestone']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this milestone')); ?>
					<?php echo $this->Html->link(null, array('controller' => 'milestones', 'action' => 'delete', $milestone['Milestone']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this milestone'), sprintf(__('Are you sure you want to delete %s?', true), $milestone['Milestone']['name'])); ?>
				</div>
				<?php if (!empty($milestone['Milestone']['description'])) : ?>
					<div id="milestone-description<?php echo $milestone['Milestone']['id']; ?>" style="display: none;" class="item-description">
						<?php echo $milestone['Milestone']['description']; ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php if ($this->Session->check('Workspace')) : ?>
	<?php echo $this->Javascript->toggle('New Milestone', array('url' => array('controller' => 'milestones', 'action' => 'add'), 'class' => 'button'));?>
<?php endif; ?>

<?php echo $this->element('layout/pagination'); ?>