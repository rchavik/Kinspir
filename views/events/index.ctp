<?php if (!empty($events)) :?>
	<div class="box">
		<h4>Events</h4>
		<ul class="item-list">
			<?php foreach ($events as $event): ?>
				<li id="event_<?php echo $event['Event']['id']; ?>" class="with-link-actions">
					<strong><?php echo $this->Html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', $event['Event']['id'])); ?></strong>
					<?php if (!empty($event['Event']['comment_count'])) : ?>
						<span class="ui-icon ui-icon-comment"></span>
					<?php endif; ?>
					<div class="hidden-link-actions right">
						<?php if (!empty($event['Event']['description'])) : ?>
							<?php echo $this->Javascript->toggle(null, array('title' => 'Show Description','class' => 'ui-icon ui-icon-folder-open', 'update' => '#event-description' . $event['Event']['id'], 'div' => false));?>
						<?php endif; ?>
						<?php echo $this->Html->link(null, array('controller' => 'events', 'action' => 'edit', $event['Event']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this event')); ?>
						<?php echo $this->Html->link(null, array('controller' => 'events', 'action' => 'delete', $event['Event']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this event'), sprintf(__('Are you sure you want to delete %s?', true), $event['Event']['name'])); ?>
					</div>
					<?php if (!empty($event['Event']['description'])) : ?>
						<div id="event-description<?php echo $event['Event']['id']; ?>" class="hidden item-description">
							<?php echo $event['Event']['description']; ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<?php echo $this->Javascript->toggle('New Event', array(
	'class' => 'button',
	'url' => array('action' => 'add')
))?>

<?php echo $this->element('layout/pagination'); ?>