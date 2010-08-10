<?php
class CommentableBehavior extends ModelBehavior {
	
	public function setup(&$Model) {
		$Model->bindModel(
			array(
				'hasMany' => array(
					'Comment' => array(
			            'className' => 'Comment',     
			            'foreignKey' => 'foreign_id', 
			            'conditions' => array('Comment.class' => $Model->name), 
			            'dependent' => true,
						'order' => 'Comment.created DESC'
					)
				)
			), false
		);
	}
	
	public function updateCommentCount(&$Model, $id) {
		$Model->id = $id;
		$conditions = array(
			'conditions' => array(
				'Comment.class' => $Model->name,
				'Comment.foreign_id' => $id
		)
		);
		$count = $Model->Comment->find('count', $conditions);
		$Model->saveField('comment_count', $count, array('callbacks' => false));
	}

}