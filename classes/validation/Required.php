<?php

/**
 * Class Required
 *
 * A specialized validator that checks if the input is not empty or null.
 *
 */
class Required extends Validator
{
	/**
	 * @var bool Determines if the validation should stop on the first failure.
	 */
	public bool $cascadeOnFailure = true;

	/**
	 * Validates the input to ensure it is not empty or null.
	 *
	 * This method checks if the input is set and not just whitespace.
	 * If the input is empty or null, a ValidationException is thrown.
	 *
	 * @return string The validated input value if it is not empty or null, or null if the input was null.
	 * @throws ValidationException If the input is empty or null.
	 */
	public function validate(): string
	{
		if (!isset($this->input) || empty(trim($this->input))) {
			throw new ValidationException("This field is required");
		}
		return $this->input;
	}
}
