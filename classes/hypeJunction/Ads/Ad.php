<?php

namespace hypeJunction\Ads;

use hypeJunction\Images\Image;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;

/**
 * Ad unit object
 *
 * @property string $address
 */
class Ad extends Image implements PublishingInterface {

	const CLASSNAME = __CLASS__;
	const TYPE = 'object';
	const SUBTYPE = 'ad_unit';

	/**
	 * Initialize object attributes
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {

		if (!$this->getContainerEntity() instanceof Campaign) {
			return false;
		}

		if (!isset($this->access_id)) {
			$this->access_id = ACCESS_PUBLIC;
		}

		if (!isset($this->published)) {
			$this->setPublishedStatus(false);
		}
		if (!isset($this->verified)) {
			$status = $this->requiresVerification() ? false : true;
			$this->setVerifiedStatus($status);
		}
		if (!$this->isVerified() || !$this->isPublished()) {
			// Only allow owners to see entities that are draft or unpublished
			if (!isset($this->future_access_id)) {
				$this->future_access_id = $this->access_id;
			}
			$this->access_id = ACCESS_PRIVATE;
		}

		if ($this->isVerified() && $this->isPublished()) {
			$this->access_id = $this->future_access_id ? $this->future_access_id : ACCESS_PUBLIC;
		}

		return parent::save();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPublishedStatus() {
		return $this->getContainerEntity()->getPublishedStatus();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPublished() {
		return $this->getContainerEntity()->isPublished();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isVerified() {
		return $this->getContainerEntity()->isVerified();
	}

	/**
	 * {@inheritdoc}
	 */
	public function requiresVerification() {
		return $this->getContainerEntity()->requiresVerification();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPublishedStatus($status = false) {
		return;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setVerifiedStatus($status = false) {
		return;
	}

	/**
	 * Log an ad impression
	 * @return void
	 */
	public function logImpression() {
		$impression = array(
			'time' => time(),
			'ip_address' => $this->getIpAddress(),
			'user_guid' => elgg_get_logged_in_user_guid(),
			'page_url' => get_input('referrer_url', current_page_url()),
		);
		$this->annotate('impression', serialize($impression));

		if (elgg_get_logged_in_user_guid() == $this->owner_guid || elgg_is_admin_logged_in()) {
			return;
		}

		$this->impressions++;
		if ($this->impressions >= 1000) {
			$amount = (string) elgg_get_plugin_setting('cost_per_mille', 'ads', '0');
			$currency = elgg_get_plugin_setting('currency', 'ads', 'EUR');
			$money = Money::fromString($amount, new Currency($currency));
			$this->getContainerEntity()->deductSpent($money->getAmount());
			$this->impressions = 0;
		}
	}

	/**
	 * Log a click
	 * @return void
	 */
	public function logClick() {
		$click = array(
			'time' => time(),
			'ip_address' => $this->getIpAddress(),
			'user_guid' => elgg_get_logged_in_user_guid(),
			'page_url' => get_input('referrer_url', current_page_url()),
		);
		$this->annotate('click', serialize($click));

		if (elgg_get_logged_in_user_guid() == $this->owner_guid || elgg_is_admin_logged_in()) {
			return;
		}

		$amount = (string) elgg_get_plugin_setting('cost_per_click', 'ads', '0');
		$currency = elgg_get_plugin_setting('currency', 'ads', 'EUR');
		$money = Money::fromString($amount, new Currency($currency));
		$this->getContainerEntity()->deductSpent($money->getAmount());
	}

	/**
	 * Get client IP
	 * @return string
	 */
	private function getIpAddress() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

}
