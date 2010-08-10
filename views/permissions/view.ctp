<script type="text/javascript">
	$(document).ready(function () {
		$("#new-permission").click(function () {
			$("#new-permission-panel").toggle("blind");
			return false;
		});

		<?php if (empty($permissions)) :?>
			$("#new-permission-panel").toggle("blind");
			$("#new-permission").hide();
		<?php endif; ?>
	});
</script>

<?php if (!empty($permissions)) :?>
	<div class="box">
		<h4><?php echo $aco['Aco']['alias']; ?> Permissions</h4>
		<ul class="item-list">
			<?php foreach ($permissions as $permission): ?>
				<li id="permission_<?php echo $permission['Permission']['id']; ?>" class="with-link-actions ui-state-default ui-corner-all hoverable">
					<strong><?php echo $this->Html->link($permission['User']['name'], array('controller' => 'users', 'action' => 'view', $permission['User']['id'])); ?></strong>
					<div class="hidden-link-actions right">
						<?php echo $this->Html->link(null, array('controller' => 'permissions', 'action' => 'delete', Inflector::underscore($permission['Permission']['model']), $permission['Permission']['aco_id'], $permission['Permission']['aro_id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this permission'), sprintf(__('Are you sure you want to delete %s?', true), $permission['User']['name'])); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	
<?php endif; ?>

<?php
	$this->Js->buffer(
		$this->Js->request(
			$this->Html->url(array('controller' => 'permissions', 'action' => 'add', $type, $id), true),
			array(
				'update' => '#new-permission-panel',
				'complete' => 'afterLoad("Permissions");'
			)
		)
	);
?>
<div id="new-permission-panel" style="display: none;"><?php echo $this->Html->image('spinner.gif');?></div>
<br /><div id="new-permission"><a href="#" class="ui-state-default ui-corner-all hoverable button">New Permission</a></div>
