<?php foreach ($workspaces as $workspace): ?>
	<div class="box" id="workspace-box-<?php echo $workspace['Workspace']['id']; ?>">
		<h4>
			<?php echo $this->Html->link($workspace['Workspace']['name'], array('controller' => 'workspaces', 'action' => 'view', $workspace['Workspace']['id']));?>
			<div class="box-actions">
				<?php if ($workspace['Workspace']['user_id'] === User::get('id')) { echo $this->Html->link(null, array('controller' => 'permissions', 'action' => 'view', 'workspace', $workspace['Workspace']['id']), array('class' => 'ui-icon ui-icon-key', 'title' => 'Manage permissions for this workspace')); }?> <?php echo $this->Html->link(null, array('controller' => 'workspaces', 'action' => 'edit', $workspace['Workspace']['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this workspace')); ?> <?php echo $this->Html->link(null, array('controller' => 'workspaces', 'action' => 'delete', $workspace['Workspace']['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this workspace'), sprintf(__('Are you sure you want to delete %s?', true), $workspace['Workspace']['name'])); ?> 
			</div>
		</h4>
		<?php if (!empty($workspace['Workspace']['description'])) : ?>
			<?php echo $workspace['Workspace']['description']; ?>
		<?php endif; ?>
	</div>
<?php endforeach; ?>

<?php if (!$this->Session->check('Workspace.id')) : ?>
	<?php echo $this->Javascript->toggle('New Workspace', array(
		'class' => 'button',
		'url' => array('controller' => 'workspaces', 'action' => 'add')
	))?>
<?php endif; ?>

<?php echo $this->element('layout/pagination')?>