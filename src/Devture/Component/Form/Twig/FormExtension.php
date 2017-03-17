<?php
namespace Devture\Component\Form\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Devture\Component\Form\Binder\BinderInterface;
use Devture\Component\Form\Token\TokenManagerInterface;

class FormExtension extends \Twig_Extension {

	private $container;

	public function __construct(\Pimple\Container $container) {
		$this->container = $container;
	}

	public function getName() {
		return 'devture_form_form_extension';
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('render_form_violations', array($this, 'renderFormViolations'), array(
				'is_safe' => array('html' => true),
				'needs_environment' => true,
			)),
			new \Twig_SimpleFunction('render_form_csrf_token', array($this, 'renderFormCsrfToken'), array(
				'is_safe' => array('html' => true),
				'needs_environment' => true,
			)),
		);
	}

	/**
	 * @return TranslatorInterface|NULL
	 */
	public function getTranslator() {
		return (isset($this->container['translator']) ? $this->container['translator'] : null);
	}

	public function renderFormViolations(\Twig_Environment $twig, BinderInterface $form, $fieldKey) {
		$errors = $form->getViolations()->get($fieldKey);
		if (count($errors) === 0) {
			return '';
		}

		$messages = array_map(function ($error) {
			return str_replace(array_keys($error['params']), array_values($error['params']), $this->translate($error['message']));
		}, $errors);
		return $twig->render('DevtureForm/validation/errors.html.twig', array('fieldKey' => $fieldKey, 'messages' => $messages));
	}

	public function renderFormCsrfToken(\Twig_Environment $twig, BinderInterface $form) {
		$tokenManager = $form->getCsrfTokenManager();
		if ($tokenManager instanceof TokenManagerInterface) {
			$token = htmlspecialchars($tokenManager->generate($form->getCsrfIntention()), ENT_QUOTES);
			return '<input type="hidden" name="' . $form->getCsrfTokenFieldName() . '" value="' . $token . '" />';
		}
		return '';
	}

	private function translate($message) {
		return ($this->getTranslator() === null ? $message : $this->getTranslator()->trans($message));
	}

}
