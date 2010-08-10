<div class="project delete form">
	<h4>You are about to permanently delete this repository</h4>
	<?php
		echo $form->create(array('action' => $this->action));
		echo $html->tag('fieldset',
		 	$form->hidden('id', array('value' => $CurrentProject->id))
		);
		echo '<div class="submit">';
		echo '<input type="submit" value="'.__('Delete it',true).'">';
		echo '<input type="submit" value="'.__('Cancel',true).'" name="cancel">';
		echo '</div>';

		echo $form->end();
	?>
</div>