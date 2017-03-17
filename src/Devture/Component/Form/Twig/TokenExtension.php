<?php
namespace Devture\Component\Form\Twig;

use Devture\Component\Form\Token\TokenManagerInterface;

class TokenExtension extends \Twig_Extension {

	private $csrfTokenManager;

	public function __construct(TokenManagerInterface $csrfTokenManager) {
		$this->csrfTokenManager = $csrfTokenManager;
	}

	public function getName() {
		return 'devture_form_token_extension';
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('csrf_token', array($this, 'getCsrfToken')),
		);
	}

	public function getCsrfToken($intention) {
		return $this->csrfTokenManager->generate($intention);
	}

}
