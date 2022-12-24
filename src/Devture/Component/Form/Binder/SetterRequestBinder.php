<?php
namespace Devture\Component\Form\Binder;

use Symfony\Component\HttpFoundation\Request;
use Devture\Component\Form\Validator\ViolationsList;
use Devture\Component\Form\Validator\ValidatorInterface;
use Devture\Component\Form\Token\TokenManagerInterface;

abstract class SetterRequestBinder implements BinderInterface {

	private ViolationsList $violations;

	private ?TokenManagerInterface $csrfTokenManager = null;
	private ?string $csrfIntention = null;

	abstract protected function doBindRequest(object $entity, Request $request, array $options = []): void;

	public function __construct(private ?ValidatorInterface $validator) {
		$this->violations = new ViolationsList();
	}

	public function getViolations(): ViolationsList {
		return $this->violations;
	}

	public function setValidator(?ValidatorInterface $validator): void {
		$this->validator = $validator;
	}

	public function getValidator(): ?ValidatorInterface {
		return $this->validator;
	}

	public function setCsrfProtection(?TokenManagerInterface $tokenManager, ?string $intention): void {
		$this->csrfTokenManager = $tokenManager;
		$this->csrfIntention = $intention;
	}

	public function getCsrfTokenManager(): ?TokenManagerInterface {
		return $this->csrfTokenManager;
	}

	public function getCsrfIntention(): ?string {
		return $this->csrfIntention;
	}

	public function getCsrfTokenFieldName(): string {
		return '_csrf_token';
	}

	public function bind(object $entity, Request $request, array $options = []): bool {
		$this->violations = new ViolationsList();

		if ($this->csrfTokenManager instanceof TokenManagerInterface) {
			$token = $request->request->get($this->getCsrfTokenFieldName());
			if (!$this->csrfTokenManager->isValid($this->csrfIntention, $token)) {
				$this->violations->add('__other__', 'Cannot confirm your identity. Reload and try again.');
				return false;
			}
		}

		$this->doBindRequest($entity, $request, $options);

		if ($this->validator instanceof ValidatorInterface) {
			$this->violations->merge($this->validator->validate($entity, $options));
		}

		return count($this->violations) === 0;
	}

	protected function bindAll(object $entity, array $values): void {
		foreach ($values as $key => $value) {
			$setter = 'set' . ucfirst($key);
			if (method_exists($entity, $setter)) {
				$entity->$setter($value);
			}
		}
	}

	protected function bindWhitelisted(object $entity, array $values, array $keysWhitelisted): void {
		$valuesAllowed = array();
		foreach ($values as $key => $value) {
			if (in_array($key, $keysWhitelisted)) {
				$valuesAllowed[$key] = $value;
			}
		}
		$this->bindAll($entity, $valuesAllowed);
	}

}
