<?php

/**
 * Class _Default
 *
 * A specialized validator that sets a default value for input if it is null.
 *
 */
class _Default extends Validator
{
	/**
	 * @var string The default value to be used if the input is null.
	 */
	private string $default;

	public function __construct(string $name, string|null $input, string $default)
	{
		parent::__construct($name, $input);
		$this->default = $default;
	}

	/**
	 * Validates the input and sets a default value if the input is null.
	 *
	 * @return string The validated input value, or the default value if the input was null.
	 */
	public function validate(): string
	{
		if (is_null($this->input)) {
			$this->input = $this->default;
		}
		return $this->input;
	}
}