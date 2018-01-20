<?php
namespace Devture\Component\Form\Validator;

class ViolationsList implements \IteratorAggregate, \Countable {

	private $violations = array();

	public function add(string $key, string $message, array $params = array()) {
		$this->violations[$key][] = array('message' => $message, 'params' => $params);
	}

	public function get(string $key): array {
		if (!isset($this->violations[$key])) {
			return array();
		}
		return $this->violations[$key];
	}

	public function merge(ViolationsList $other) {
		foreach ($other->violations as $key => $items) {
			foreach ($items as $item) {
				$this->violations[$key][] = $item;
			}
		}
	}

	public function getIterator() {
		return new \ArrayIterator($this->violations);
	}

	public function count(): int {
		return count($this->violations);
	}

}
