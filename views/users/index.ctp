<div class="users index form">
	<h4>
		<?php __('Users');?> who have access to <?php echo $this->params['project']; ?>
	</h4>
	<?php if (!empty($groups)):?>
		<?php echo $form->create(array('action' => 'index')); ?>
	<?php endif; ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<?php if (!empty($groups)):?>
				<th class="left">
					<?php echo $paginator->sort(__('Group',true), 'ProjectPermission.group');?>
				</th>
			<?php endif; ?>
			<th><?php echo $paginator->sort(__('Username',true),'username');?></th>
			<th><?php echo $paginator->sort(__('Email',true),'email');?></th>
			<th><?php echo $paginator->sort(__('Last Login',true),'last_login');?></th>
			<th>&nbsp;</th>
		</tr>
	<?php
	$i = 0;
	foreach ($users as $i => $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<?php if (!empty($groups)):?>
				<td class="left">
					<?php
						echo $form->hidden("ProjectPermission.{$i}.id", array('value' => $user['ProjectPermission']['id']));
						echo $form->select("ProjectPermission.{$i}.group", $groups, $user['ProjectPermission']['group'], array('empty' => false));
					?>
				</td>
			<?php endif; ?>
			<td>
				<?php echo $user['User']['username']; ?>
			</td>
			<td>
				<?php echo $user['User']['email']; ?>
			</td>
			<td>
				<?php echo $user['User']['last_login']; ?>
			</td>
			<td class="actions">
				<?php
					if (!empty($this->passedArgs['all']) && !empty($this->params['isAdmin'])) {
						echo $chaw->admin(__('edit',true), array('controller' => 'users', 'action' => 'edit', $user['User']['id']));
						echo $chaw->admin(__('remove',true), array('controller' => 'users', 'action' => 'remove', $user['User']['id']));
					} else {
						echo $chaw->admin(__('remove',true), array('controller' => 'project_permissions', 'action' => 'remove', $user['ProjectPermission']['id']));
					}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php if (!empty($groups)):?>
		<br />
		<p class="clear">
			use groups for an easy way to setup <?php echo $chaw->admin('permissions', array('controller' => 'project_permissions'));?>
		</p>
		<fieldset>
			<?php echo $form->end('save');?>
		</fieldset>
	<?php endif; ?>
</div>
<?php echo $this->element('layout/pagination'); ?>
<div class="clearFix">&nbsp;</div>
<?php if (!empty($groups) && !empty($this->params['isAdmin'])):?>
	<div class="users add form">
	<h4>Add User</h4>
	<?php 
			echo $form->create(array('action' => 'index', 'id' => 'UserAddForm'));
	?>
		<fieldset>
			<?php
				echo $form->input('username');
				echo $form->input('group');
			?>
			<div class="clearFix"></div>
		</fieldset>
		<?php
			echo $form->end('add');
	?>
	</div>
<?php endif; ?>