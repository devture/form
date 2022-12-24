<?php
namespace Devture\Component\Form\Binder;

use Symfony\Component\HttpFoundation\Request;
use Devture\Component\Form\Validator\ViolationsList;
use Devture\Component\Form\Validator\ValidatorInterface;
use Devture\Component\Form\Token\TokenManagerInterface;

interface BinderInterface {

	/**
	 * The violations that occurred during the last `bind()` call.
	 */
	public function getViolations(): ViolationsList;

	public function setValidator(?ValidatorInterface $validator): void;

	public function getValidator(): ?ValidatorInterface;

	public function setCsrfProtection(?TokenManagerInterface $tokenManager, ?string $intention);

	public function getCsrfTokenManager(): ?TokenManagerInterface;

	public function getCsrfIntention(): ?string;

	public function getCsrfTokenFieldName(): string;

	/**
	 * Binds the request parameters to the provided entity.
	 * Returns false if any violations occur during binding or validation.
	 *
	 * @return boolean - whether binding (and subsequent validation, if enabled) were successful
	 */
	public function bind(object $entity, Request $request, array $options = []): bool;

}
