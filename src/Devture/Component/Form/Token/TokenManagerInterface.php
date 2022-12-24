<?php
namespace Devture\Component\Form\Token;

interface TokenManagerInterface {

	public function setSalt(string $salt);

	public function generate(string $intention): string;

	public function isValid(string $intention, string $token): bool;

}
