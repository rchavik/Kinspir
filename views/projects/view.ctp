<div class="project">

	<h2 class="name">
		<?php echo $html->link($project['Project']['name'], array(
			'username' => $project['Project']['username'],
			'project' => $project['Project']['url'],
			'controller' => 'projects', 'action' => 'view'
		));?>
	</h2>
	<?php
		if (empty($project['Project']['approved'])) {
			echo $html->tag('span', 'Awaiting Approval', array('class' => 'inactive'));
		}
	?>
	<?php
		if (!empty($project['Project']['private'])) {
			echo $html->tag('span', 'Private', array('class' => 'active'));
		}
	?>
	
	<p class="description">
		<?php echo $project['Project']['description'];?>
	</p>
	
	<h4>Groups</h4>
	<?php echo $project['Project']['config']['groups'];?>

</div>