<?php
namespace Devture\Component\Form\Token;

use Devture\Component\Form\Helper\StringHelper;

class TemporaryTokenManager implements TokenManagerInterface {

	private string $salt = '';

	public function __construct(
		private int $validityTime,
		private string $secret,
		private string $hashFunction,
	) {
	}

	public function setSalt(string $salt): void {
		$this->salt = $salt;
	}

	public function generate(string $intention): string {
		return $this->generateToken($intention, time());
	}

	public function isValid(string $intention, string $token): bool {
		if (strpos($token, '-') === false) {
			return false;
		}

		list($timestamp, $_hash) = explode('-', $token, 2);

		if (!is_numeric($timestamp)) {
			return false;
		}

		$timestamp = (int) $timestamp;
		if ($timestamp > time() || $timestamp + $this->validityTime < time()) {
			return false;
		}

		return hash_equals($this->generateToken($intention, $timestamp), $token);
	}

	private function generateToken(string $intention, int $timestamp): string {
		return $timestamp . '-' . hash_hmac($this->hashFunction, $timestamp . $intention, $this->secret . $this->salt);
	}

}
