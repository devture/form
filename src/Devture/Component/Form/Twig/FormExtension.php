<?php
namespace Devture\Component\Form\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Devture\Component\Form\Binder\BinderInterface;
use Devture\Component\Form\Token\TokenManagerInterface;

class FormExtension extends \Twig\Extension\AbstractExtension {

	private ?TranslatorInterface $translator = null;

	public function __construct(?TranslatorInterface $translator = null) {
		$this->translator = $translator;
	}

	public function getName(): string {
		return 'devture_form_form_extension';
	}

	public function getFunctions(): array {
		return [
			new \Twig\TwigFunction('devture_form_render_violations', array($this, 'renderFormViolations'), array(
				'is_safe' => array('html' => true),
				'needs_environment' => true,
			)),
			new \Twig\TwigFunction('devture_form_render_csrf_token', array($this, 'renderFormCsrfToken'), array(
				'is_safe' => array('html' => true),
				'needs_environment' => true,
			)),
		];
	}

	public function renderFormViolations(\Twig\Environment $twig, BinderInterface $form, $fieldKey) {
		$errors = $form->getViolations()->get($fieldKey);
		if (count($errors) === 0) {
			return '';
		}

		$messages = array_map(function ($error) {
			return str_replace(array_keys($error['params']), array_values($error['params']), $this->translate($error['message']));
		}, $errors);
		return $twig->render('DevtureForm/validation/errors.html.twig', array('fieldKey' => $fieldKey, 'messages' => $messages));
	}

	public function renderFormCsrfToken(\Twig\Environment $twig, BinderInterface $form) {
		$tokenManager = $form->getCsrfTokenManager();
		if ($tokenManager instanceof TokenManagerInterface) {
			$token = htmlspecialchars($tokenManager->generate($form->getCsrfIntention()), ENT_QUOTES);
			return '<input type="hidden" name="' . $form->getCsrfTokenFieldName() . '" value="' . $token . '" />';
		}
		return '';
	}

	private function translate(string $message): string {
		return ($this->translator === null ? $message : $this->translator->trans($message));
	}

}
