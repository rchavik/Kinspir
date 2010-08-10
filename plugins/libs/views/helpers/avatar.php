<?php
/*
 * Kinspir.Libs is free software, you can redistribute it and/or modify
 * it under the terms of GNU Affero General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or (at your option) any later version.

 * You should have received a copy of the the GNU Affero
 * General Public License, along with Kinspir.Libs. If not, see

 * Additional permission under the GNU Affero GPL version 3 section 7:

 * If you modify this Program, or any covered work, by linking or
 * combining it with other code, such other code is not for that reason
 * alone subject to any of the requirements of the GNU Affero GPL
 * version 3.
 */
App::import(array('Security', 'Validation'));

/**
 * Avatar Helper
 *
 */
class AvatarHelper extends GravatarHelper {

/**
 * Facebook default settings
 *
 * @var array
 * @access private
 */
	private $__facebook = array(
		'fb_size' => 'square',
		'linked' => 'false',
		'facebook-logo' => 'true'
	);

/**
 * Constructor
 *
 * @access public
 */
	public function __construct() {
		parent::__construct();
		$this->__default['class'] = 'avatar';
	}

/**
 * Show avatar for the supplied user (email address or facebook_id)
 */
	public function image($user, $options = array()) {
		if (!empty($user['User'])) {
			$user = $user['User'];
		}
		if (!empty($user['facebook_id'])) {
			return $this->__facebookImage($user, $options);
		}
		unset($options['fb_size'], $options['linked'], $options['facebook-logo']);
		return parent::image($user['email'], $options);
	}

/**
 * Show linked avatar for the supplied user (email address or facebook_id)
 */
	public function link($user, $url = array(), $options = array()) {
		if (!empty($user['User'])) {
			$user = $user['User'];
		}
		if (empty($url)) {
			$url = array('controller' => 'users', 'action' => 'view', $user['id']);
		}
		// generage our image html/xfbml
		$image = $this->image($user, $options);
		// merge our options
		$options = array_merge($this->__default, $options);
		// unset bad options
		unset($options['fb_size'], $options['linked'], $options['facebook-logo'], $options['default'], $options['ext'], $options['rating'], $options['secure'], $options['size']);
		// escape the html in the link
		$options['escape'] = false;
		// return link
		return $this->Html->link($image, $url, $options);
	}

/**
 * Genrate XFBML to display facebook avatar
 */
	private function __facebookImage($user, $options = array()) {
		$options = $this->__facebookImageOptions($options);
		return '<fb:profile-pic uid="' . $user['facebook_id'] . '" facebook-logo="' . $options['facebook-logo'] . '" linked="' . $options['linked'] . '" size="' . $options['fb_size'] . '" width="' . $options['width'] . '" height="' . $options['height'] . '"></fb:profile-pic>';
	}

/**
 * Clean up the options for facebookImage
 */
	private function __facebookImageOptions($options = array()) {
		$options = $this->__cleanOptions(Set::merge($this->__default, $this->__facebook, $options));
		if (empty($options['height'])) {
			$options['height'] = $options['size'];
		}
		if (empty($options['width'])) {
			$options['width'] = $options['size'];
		}
		return $options;
	}

}