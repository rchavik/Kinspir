<?php
class ReadableBehavior extends ModelBehavior {
	
	private function __validateUsers(&$Model, $userIds, $messageId) {
		$conditions = array(
			'conditions' => array(
				'UnreadMessage.message_id' => $messageId
			)
		);
		$currentUnreadUsers = ClassRegistry::init('UnreadMessage')->find('all', $conditions);
		$currentUnreadUsers = Set::extract('/UnreadMessage/user_id', $currentUnreadUsers);
		return array_diff($userIds, (array)$currentUnreadUsers, (array)User::get('id'));
	}
	
	public function setUnread(&$Model, $userIds, $messageId) {
		$userIds = (array)$userIds;
		$newUnreadUsers = $this->__validateUsers($Model, $userIds, $messageId);
		foreach ($newUnreadUsers as $newUnreadUser) {
			$unreadMessages['UnreadMessage'][] = array(
				'message_id' => $messageId,
				'user_id' => $newUnreadUser
			);
		}
		if (!empty($unreadMessages['UnreadMessage']) && !Classregistry::init('UnreadMessage')->saveAll($unreadMessages['UnreadMessage'])) {
			return;
		}
		return true;
	}
	
	public function setRead(&$Model, $messageId) {
		$deleteConditions = array(
			'UnreadMessage.user_id' => User::get('id'),
			'UnreadMessage.message_id' => $messageId
		);
		$Model->UnreadMessage->deleteAll($deleteConditions);
	}
	
	public function afterDelete(&$Model) {
		$data = array(
			'message_id' => $Model->id
		);
		Classregistry::init('UnreadMessage')->deleteAll($data);
		return true;
	}
	
}