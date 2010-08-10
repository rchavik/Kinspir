<?php
class MessagesController extends AppController {

	/**
	 * 
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Message->MessageFolder = ClassRegistry::init('MessageFolder');
	}
	
	// @TODO Clean this function up
	public function index($folder = null) {
		if (!$folder) {
			$folder = 'inbox';
		}

		$messagePanelUrl = array('action' => 'index', $folder);
		$messageFolders = $this->Message->MessageFolder->find('list', array(
			'conditions' => array(
				'user_id' => User::get('id')
			)
		));
		$this->set(compact('messagePanelUrl', 'messageFolders'));

		$deletedMessageIds = $this->Message->DeletedMessage->findAllByUserId(User::get('id'));
		$deletedMessageIds = Set::extract('/DeletedMessage/message_id', $deletedMessageIds);
		switch ($folder) {
			case 'inbox':
				$subscribedMessageIds = $this->Message->Subscription->findAllByUserIdAndClass(User::get('id'), 'Message');
				$subscribedMessageIds = Set::extract('/Subscription/foreign_id', $subscribedMessageIds);
				$locatedMessageIds = $this->Message->MessageLocation->findAllByUserId(User::get('id'));
				$locatedMessageIds = Set::extract('/MessageLocation/message_id', $locatedMessageIds);
				$ignoredIds = array_merge($deletedMessageIds, $locatedMessageIds);
				$this->paginate = array(
					'contain' => array(
						'User',
						'RootMessage' => array(
							'Replier'
						),
						'UnreadMessage' => array (
							'conditions' => array(
								'UnreadMessage.user_id' => User::get('id')
							)
						),
						'MessageLocation'
					),
					'conditions' => array(
						'Message.id' => $subscribedMessageIds,
						'NOT' => array(
							'Message.id' => $ignoredIds,
							'AND' => array(
								'Message.reply_count' => NULL,
								'Message.user_id' => User::get('id')
							)
						)
					),
					'aclConditions' => array(),
					'order' => array('Message.last_reply' => 'DESC')
				);
				break;
			case 'sent':
				$this->paginate = array(
					'contain' => array('User', 'RootMessage' => array('Replier')),
					'conditions' => array(
						'Message.user_id' => User::get('id'),
						'NOT' => array(
							'Message.id' => $deletedMessageIds
						)
					),
					'order' => array('Message.id' => 'DESC')
				);
				break;
			case 'trash':
				$this->paginate = array(
					'contain' => array('User', 'DeletedMessage', 'RootMessage' => array('Replier')),
					'conditions' => array(
						'Message.id' => $deletedMessageIds
					),
					'aclConditions' => array(),
					'order' => array('Message.last_reply' => 'DESC')
				);
				break;
			default:
				$folderId = $this->Tools->keyToId($folder, 'MessageFolder', 'name', 'user_id', User::get('id'));
				if (!$this->Message->MessageFolder->checkOwner($folderId)) {
					$this->Redirect->flash('no_data', array('action' => 'index'));
					break;
				}
				$subscribedMessageIds = $this->Message->Subscription->findAllByUserId(User::get('id'));
				$subscribedMessageIds = Set::extract('/Subscription/foreign_id', $subscribedMessageIds);
				$locatedMessageIds = $this->Message->MessageLocation->findAllByUserIdAndMessageFolderId(User::get('id'), $folderId);
				$locatedMessageIds = Set::extract('/MessageLocation/message_id', $locatedMessageIds);
				$messageIds = array_intersect($subscribedMessageIds, $locatedMessageIds);
				$this->paginate = array(
					'contain' => array(
						'User',
						'RootMessage' => array(
							'Replier'
						),
						'UnreadMessage' => array (
							'conditions' => array(
								'UnreadMessage.user_id' => User::get('id')
							)
						),
						'MessageLocation'
					),
					'conditions' => array(
						'Message.id' => $messageIds,
						'NOT' => array(
							'Message.id' => $deletedMessageIds,
							'Message.reply_count' => NULL
						)
					),
					'aclConditions' => array(),
					'order' => array('Message.last_reply' => 'DESC')
				);
		}
		$this->set(
			'messages',
			$this->paginate()
		);
		$this->set(compact('folder'));
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		$messagePanelUrl = array('action' => 'view', $id);
		$messageFolders = $this->Message->MessageFolder->find('list', array(
			'conditions' => array(
				'user_id' => User::get('id')
			)
		));
		$this->set(compact('messagePanelUrl', 'messageFolders'));
		$message = $this->Message->findById($id);
		if (!empty($message['Message']['root_id'])) {
			$rootId = $message['Message']['root_id'];
			$conditions = array(
				'conditions' => array(
					'Message.root_id' => $rootId
				),
				'order' => array('Message.id' => 'ASC'),
				'contain' => array(
					'User',
					'Replier'
				)
			);
			$this->set(
				'messages',
				$this->{$this->modelClass}->find('all', $conditions)
			);
			$this->Message->setRead($rootId);
		} else {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
	}

	public function edit($id = null) {
		$users = $this->_userConnections();
		$users = $this->Message->User->findList(
			array(
				'conditions' => array(
					'User.id' => $users
				)
			)
		);
		if ($id) {
			$lastChild = $this->Message->getChildren($id,
				array(
					'limit' => 1,
					'sort' => 'desc'
				)
			);
			if (!empty($lastChild[0]['Message'])) {
				$lastChild = $lastChild[0]['Message'];
				$id = $lastChild['id'];
				$rootId = $lastChild['root_id'];
			} else {
				$lastChild = $this->Message->findById($id);
				$rootMessage = $lastChild['Message'];
				$rootId = $rootMessage['root_id'];
			}
			$subscribers = $this->Message->getSubscribers($rootId);
			$subscribers = $this->Message->User->findList(
				array(
					'conditions' => array(
						'User.id' => $subscribers
					)
				)
			);
			foreach ($subscribers as $subscriber) {
				$key = array_search($subscriber, $users);
				if ($key) {
					unset($users[$key]);
				}
			}
			$this->set('parent_id', $id);
			$this->set(compact('subscribers'));
		}
		$this->set(compact('users'));

		if (!empty($this->data)) {
			if (!empty($this->data['Message']['parent_id'])) {
				$lastChild = $this->Message->getChildren($this->data['Message']['parent_id'],
					array(
						'limit' => 1,
						'sort' => 'desc'
					)
				);
				if (!empty($lastChild[0]['Message'])) {
					$lastChild = $lastChild[0]['Message'];
					$this->data['Message']['parent_id'] = $lastChild['id'];
					$rootMessageId = $lastChild['root_id'];
				} else {
					$lastChild = $this->Message->findById($this->data['Message']['parent_id']);
					$this->data['Message']['parent_id'] = $lastChild['Message']['id'];
					$rootMessageId = $lastChild['Message']['root_id'];
				}
			} else {
				$this->data['Recipients'][] = User::get('id');
			}
			$recipients = null;
			if (!empty($this->data['Recipients'])) {
				$recipients = $this->data['Recipients'];
				unset($this->data['Recipients']);
			}
		}

		if (parent::edit($id, true, false) && !empty($this->data)) {
			if (!empty($this->data['Message']['parent_id'])) {
				$notificationKey = 'replied_to_message';
				$redirectLocation = array('action'=>'index', 'inbox');
			} else {
				$rootMessageId = $this->Message->id;
				$notificationKey = 'received_message';
				$redirectLocation = array('action'=>'index', 'inbox');
			}
			$this->Message->subscribe($recipients, $rootMessageId);
			$subscribers = $this->Message->getSubscribers($rootMessageId);
			$this->_allow($subscribers, array('Message'=>array('id'=>$rootMessageId)));
			$this->Message->setUnread($subscribers, $rootMessageId);
			$this->Message->notify($subscribers, $rootMessageId, $this->Tools->keyToId($notificationKey, 'NotificationType'));
			$this->Redirect->flash(array('add_ok', Inflector::humanize(strtolower($this->modelClass))), $redirectLocation);
		}
	}

	public function restore($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		$ids = (array)$id;

		$conditions = array(
			'conditions' => array(
				'DeletedMessage.message_id' => $ids,
				'DeletedMessage.user_id' => User::get('id')
			)
		);
		$ids = $this->Message->DeletedMessage->find('all', $conditions);

		if ($ids) {
			$ids = Set::extract('/DeletedMessage/id', $ids);
			$count = count($ids);
			if ($this->Message->DeletedMessage->deleteAll(array('DeletedMessage.id' => $ids))) {
				$this->Redirect->flash(array('delete_ok', $count), array('action' => 'index'));
			}
		}

		$this->Redirect->flash('no_data', array('action' => 'index'));
	}

	public function delete($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		$ids = (array)$id;
		
		$conditions = array(
			// @todo if I don't have this contain, it breaks aclConditions, figure out why
			'contain' => array(
				'User' => array(
					'fields' => array('id', 'name')
				)
			),
			'conditions' => array(
				'Message.id' => $ids
			),
			'aclConditions' => array()
		);
		$ids = $this->Message->find('all', $conditions);

		if ($ids) {
			$ids = Set::extract('/Message/id', $ids);
			$count = count($ids);
			foreach ($ids as $id) {
				$data['DeletedMessage'][] = array(
					'message_id' => $id,
					'user_id' => User::get('id')
				);
			}
			if ($this->Message->DeletedMessage->saveAll($data['DeletedMessage'])) {
				$this->Redirect->flash(array('delete_ok', $count), array('action' => 'index'));
			}
		}
		
		$this->Redirect->flash('no_data', array('action' => 'index'));
	}
}