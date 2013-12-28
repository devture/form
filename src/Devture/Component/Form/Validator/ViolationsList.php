<?php
namespace Devture\Component\Form\Validator;

class ViolationsList implements \IteratorAggregate, \Countable {

	private $violations = array();

	public function add($key, $message, array $params = array()) {
		$this->violations[$key][] = array('message' => $message, 'params' => $params);
	}

	public function get($key) {
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

	public function count() {
		return count($this->violations);
	}

}
