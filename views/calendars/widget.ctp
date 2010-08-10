<div class="box">
	<h4>Calendar</h4>
	<script type="text/javascript">
		$(function() {
			$('#datepicker').datepicker({
				inline: true
			});
		});
	</script>
	<div id="datepicker"></div>
	<ul class="list-links">
		<li><?php echo $this->Html->link('Create Event', array('controller' => 'events', 'action' => 'add'), array('class' => 'create'))?></li>
		<li><?php echo $this->Html->link('Create Milestone', array('controller' => 'milestones', 'action' => 'add'), array('class' => 'create'))?></li>
	</ul>
</div>