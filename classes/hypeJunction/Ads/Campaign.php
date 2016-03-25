<?php

namespace hypeJunction\Ads;

use ElggObject;

/**
 * Ad campaign object
 *
 * @property bool $published
 * @property bool $verified
 * @property int  $budget
 */
class Campaign extends ElggObject implements PublishingInterface {

	const CLASSNAME = __CLASS__;
	const TYPE = 'object';
	const SUBTYPE = 'ad_campaign';

	/**
	 * Initialize object attributes
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Get campaign ads
	 * 
	 * @param mixed $subtypes Ad entity subtypes
	 * @return \ElggBatch
	 */
	public function getAds($subtypes = null) {
		if (empty($subtypes)) {
			$subtypes = array(Ad::SUBTYPE);
		}
		return new \ElggBatch('elgg_get_entities', array(
			'types' => 'object',
			'subtypes' => $subtypes,
			'container_guids' => (int) $this->guid,
			'limit' => 0,
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {

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
			foreach ($this->getAds() as $ad) {
				$ad->save();
			}
		}

		return parent::save();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPublished() {
		return ($this->published);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isVerified() {
		return ($this->verified);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPublishedStatus() {
		if ($this->isPublished() && $this->isVerified()) {
			return 'active';
		} else if ($this->isPublished() && !$this->isVerified()) {
			return 'pending';
		} else {
			return 'draft';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function requiresVerification() {
		return !$this->isVerified() && elgg_get_plugin_setting('requires_verification', 'ads', false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPublishedStatus($status = false) {
		$this->published = $status;
		if ($this->published) {
			elgg_trigger_event('publish', 'object', $this);
		} else {
			elgg_trigger_event('unpublish', 'object', $this);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setVerifiedStatus($status = false) {
		$this->verified = $status;
		if ($this->published) {
			elgg_trigger_event('verify', 'object', $this);
		} else {
			elgg_trigger_event('unverify', 'object', $this);
		}
	}

	/**
	 * Add budget
	 *
	 * @param int $amount Amount to add
	 * @return void
	 */
	public function updateBudget($amount = 0) {
		$this->budget += $amount;
		if ($this->budget - $this->spent > 0) {
			$this->setPublishedStatus(true);
		} else {
			$this->setPublishedStatus(false);
		}
		$this->save();
	}

	/**
	 * Return budget of the campaign
	 * @return int
	 */
	public function getBudget() {
		return $this->budget;
	}

	/**
	 * Deduct spending
	 *
	 * @param int $amount Amount to deduct
	 * @return void
	 */
	public function deductSpent($amount = 0) {
		$this->spent += $amount;
		if ($this->budget - $this->spent <= 0) {
			$this->setPublishedStatus(false);
			$this->save();
		}
	}

	/**
	 * Returns spent amount
	 * @return int
	 */
	public function getSpent() {
		return (int) $this->spent;
	}

}
