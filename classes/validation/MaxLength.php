<?php

/**
 * Class MaxLength
 *
 * A specialized validator that checks if the input length does not exceed a maximum length.
 *
 */
class MaxLength extends Validator
{
	/**
	 * @var int The maximum length allowed for the input string.
	 */
	private int $maxLength;

	public function __construct(string $name, string|null $input, int $maxLength)
	{
		parent::__construct($name, $input);
		$this->maxLength = $maxLength;
	}

	/**
	 * Validates the input to ensure it does not exceed the specified maximum length.
	 *
	 * This method checks if the length of the input string is less than or equal to the maximum length.
	 * If the input length exceeds the maximum length, a ValidationException is thrown.
	 *
	 * @return string|null The validated input value if its length does not exceed the maximum length, or null if the input was null.
	 * @throws ValidationException If the input length exceeds the specified maximum length.
	 */
	public function validate(): string|null
	{
		if (strlen($this->input) > $this->maxLength) {
			throw new ValidationException("Must not be more than $this->maxLength characters");
		}
		return $this->input;
	}
}