<?php
namespace Devture\Component\Form\Token;

use Devture\Component\Form\Helper\StringHelper;

class TemporaryTokenManager implements TokenManagerInterface {

	private $validityTime;
	private $secret;
	private $hashFunction;
	private $salt;

	public function __construct($validityTime, $secret, $hashFunction) {
		$this->validityTime = $validityTime;
		$this->secret = $secret;
		$this->hashFunction = $hashFunction;
		$this->salt = '';
	}

	public function setSalt($salt) {
		$this->salt = $salt;
	}

	public function generate($intention) {
		return $this->generateToken($intention, time());
	}

	public function isValid($intention, $token) {
		if (strpos($token, '-') === false) {
			return false;
		}

		list($timestamp, $_hash) = explode('-', $token, 2);

		if (!is_numeric($timestamp)) {
			return false;
		}

		$timestamp = (int)$timestamp;
		if ($timestamp > time() || $timestamp + $this->validityTime < time()) {
			return false;
		}

		return StringHelper::equals($this->generateToken($intention, $timestamp), $token);
	}

	private function generateToken($intention, $timestamp) {
		return $timestamp . '-' . hash($this->hashFunction, $timestamp . $intention . $this->secret . $this->salt);
	}

}
