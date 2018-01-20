<?php
namespace Devture\Component\Form\Helper;

class StringHelper {

	/**
	 * Compares two strings.
	 *
	 * Function from the Symfony Security component by Fabien Potencier.
	 * Original source: https://github.com/symfony/symfony/blob/a4d423e4cd576b5/src/Symfony/Component/Security/Core/Util/StringUtils.php
	 *
	 * This method implements a constant-time algorithm to compare strings.
	 *
	 * @param string $knownString The string of known length to compare against
	 * @param string $userInput The string that the user can control
	 *
	 * @return Boolean true if the two strings are the same, false otherwise
	 */
	static public function equals($knownString, $userInput): bool {
		// Prevent issues if string length is 0
		$knownString .= chr(0);
		$userInput .= chr(0);

		$knownLen = strlen($knownString);
		$userLen = strlen($userInput);

		// Set the result to the difference between the lengths
		$result = $knownLen - $userLen;

		// Note that we ALWAYS iterate over the user-supplied length
		// This is to prevent leaking length information
		for ($i = 0; $i < $userLen; $i++) {
			// Using % here is a trick to prevent notices
			// It's safe, since if the lengths are different
			// $result is already non-0
			$result |= (ord($knownString[$i % $knownLen]) ^ ord($userInput[$i]));
		}

		// They are only identical strings if $result is exactly 0...
		return 0 === $result;
	}

}