<script type='text/javascript'>
	$(document).ready(function() {
		$('#full-calendar').fullCalendar({
			header: {
				left: 'today',
				center: 'prev, title, next',
				right: ''
			},
			editable: false,
			eventSources: [
				'<?php echo $this->Html->url(array('controller'=>'events', 'action'=>'index.json')); ?>',
				'<?php echo $this->Html->url(array('controller'=>'milestones', 'action'=>'index.json')); ?>',
				'<?php echo $this->Html->url(array('controller'=>'tasks', 'action'=>'index.json')); ?>'
			],
			theme: true,
			loading: function(bool) {
				if (bool) $('.fc-header-right').addClass('loading').attr('style','display:block;width:100%;height:50px');
				else $('.fc-header-right').removeClass('loading');
			}
		});
	});
</script>

<div class="box">
	<h4>Calendar</h4>
	<div id="full-calendar"></div>
</div>


<?php 
if ($this->Session->check('Workspace')):
$this->Layout->blockStart('sidebar')
?>
<div class="box">
	<h4>Actions</h4>
	<ul class="list-links">
		<li><?php echo $this->Javascript->link('Events', array('controller' => 'events', 'action' => 'index'), '#content-main')?></li>
		<li><?php echo $this->Javascript->link('Create Event', array('controller' => 'events', 'action' => 'add'), '#content-main', array('class' => 'create'))?></li>
		<li><?php echo $this->Javascript->link('Milestones', array('controller' => 'milestones', 'action' => 'index'), '#content-main')?></li>
		<li><?php echo $this->Javascript->link('Create Milestone', array('controller' => 'milestones', 'action' => 'add'), '#content-main', array('class' => 'create'))?></li>
	</ul>
</div>
<?php 
$this->Layout->blockEnd();
endif;
?>