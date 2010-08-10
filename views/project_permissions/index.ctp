<?php //@todo embed variable text ?>
<div class="permissions form">
 	<h4><?php __('Manage Permissions');?></h4>
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset>
		<?php
			echo $form->input('fine_grained', array('type' => 'textarea'));
		?>
	</fieldset>

<?php
	echo '<div class="submit">';
	echo '<input type="submit" value="'.__('Save',true).'">';
	//echo '<input type="submit" value="'.__('Reload Defaults',true).'" name="default">';
	echo '</div>';

echo $form->end();

?>
	<div class="help">
		<h2><?php __('Guide'); ?></h2>
		<br />
		<h3><?php __('access rights') ?></h3>
		<p class="rule">
			r - <?php __('read') ?><br/>
			w - <?php __('write (c, u, d)') ?><br/>
			<?php
				$example = 'rw';
				if($CurrentProject->repo->type != 'svn'): $example = 'cr';
			?>
				c - <?php __('create') ?><br/>
				u - <?php __('update') ?><br/>
				d - <?php __('delete') ?><br/>
			<?php endif;?>
		</p>
		<br />
		<p>
			<h3><?php __('your groups') ?> - 
			<?php echo $chaw->admin('manage', array(
				'admin' => false, 'controller' => 'projects', 'action' => 'edit'
			))?></h3>
		</p>
		<p class="rule">
			<?php echo join(', ', $groups);?>
		</p>
		<br />
		<h3><?php __('how to create groups') ?></h3>
		<p class="rule">
			[groups]<br/>
			team = user1, user2
		</p>
		<br />
		<h3><?php __('allowing access') ?></h3>
		<p class="rule">
			[source]<br/>
			@team = r
		</p>
		<br />
		<p class="rule">
			[commits]<br/>
			@team = r
		</p>
		<br />
		<p class="rule">
			[timeline]<br/>
			@team = r
		</p>
	</div>
</div>
