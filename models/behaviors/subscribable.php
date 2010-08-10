<?php
class SubscribableBehavior extends ModelBehavior {
	
	public function getSubscribers(&$Model, $foreignId) {
		$conditions = array(
			'conditions' => array(
				'Subscription.class' => $Model->name,
				'Subscription.foreign_id' => $foreignId
			)
		);
		$subscribers = ClassRegistry::init('Subscription')->find('all', $conditions);
		$subscribers = Set::extract('/Subscription/user_id', $subscribers);
		return $subscribers;
	}
	
	public function subscribe(&$Model, $userIds, $foreignId) {
		$userIds = (array)$userIds;
		$subscription = array(
			'class' => $Model->name,
			'foreign_id' => $foreignId
		);
		$currentSubscribers = $this->getSubscribers($Model, $foreignId);
		$newSubscribers = array_diff($userIds, (array)$currentSubscribers);
		foreach ($newSubscribers as $newSubscriber) {
			$subscriptions['Subscription'][] = array_merge(
				$subscription,
				array('user_id' => $newSubscriber)
			);
		}
		if (!empty($subscriptions['Subscription'])) {
			ClassRegistry::init('Subscription')->saveAll($subscriptions['Subscription']);
		}
		return true;
	}
	
	public function unsubscribe(&$Model, $userIds, $foreignId) {
		$userIds = (array)$userIds;
		$data = array(
			'class' => $Model->name,
			'foreign_id' => $foreignId,
			'user_id' => $userIds
		);
		Classregistry::init('Subscription')->deleteAll($data);
		return true;
	}
	
	public function afterDelete(&$Model) {
		$data = array(
			'class' => $Model->name,
			'foreign_id' => $Model->id
		);
		Classregistry::init('Subscription')->deleteAll($data);
		return true;
	}
	
}