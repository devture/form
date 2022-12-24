<?php
namespace Devture\Component\Form\Twig;

use Devture\Component\Form\Token\TokenManagerInterface;

class TokenExtension extends \Twig\Extension\AbstractExtension {

	public function __construct(private TokenManagerInterface $csrfTokenManager) {
	}

	public function getName(): string {
		return 'devture_form_token_extension';
	}

	public function getFunctions(): array {
		return [
			new \Twig\TwigFunction('devture_csrf_token', array($this, 'getCsrfToken')),
		];
	}

	public function getCsrfToken(string $intention): string {
		return $this->csrfTokenManager->generate($intention);
	}

}
