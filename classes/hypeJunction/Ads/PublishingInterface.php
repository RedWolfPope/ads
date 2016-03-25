<?php

namespace hypeJunction\Ads;

/**
 * Interface for publishing and approval flow
 */
interface PublishingInterface {

	/**
	 * Check if entity is published
	 * @return bool
	 */
	public function isPublished();

	/**
	 * Check if entity is verified
	 * @return bool
	 */
	public function isVerified();

	/**
	 * Check if entity requires verifications
	 * @return bool
	 */
	public function requiresVerification();

	/**
	 * Publish an entity
	 *
	 * @param bool $status Published status
	 * @return bool
	 */
	public function setPublishedStatus($status = false);

	/**
	 * Set verification status
	 *
	 * @param bool $status Verified status
	 * @return bool
	 */
	public function setVerifiedStatus($status = false);

	/**
	 * Returns human readable status
	 * @return string
	 */
	public function getPublishedStatus();
	
}
