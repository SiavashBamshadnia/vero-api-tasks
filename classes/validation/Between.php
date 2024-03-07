<?php

/**
 * Class Between
 *
 * A specialized validator that checks if the input is within a set of allowed values.
 *
 */
class Between extends Validator
{
	/**
	 * @var array The set of allowed values for the input.
	 */
	private array $items;

	public function __construct(string $name, string|null $input, ...$items)
	{
		parent::__construct($name, $input);
		$this->items = $items;
	}

	/**
	 * Validates the input to ensure it is one of the specified allowed values.
	 *
	 * @return string|null The validated input value if it is within the allowed values.
	 * @throws ValidationException If the input is not null and not within the allowed values.
	 */
	public function validate(): string|null
	{

		if (!is_null($this->input) && !in_array($this->input, $this->items)) {
			$s = "'" .implode("', '",$this->items) . "'";
			throw new ValidationException("This field must be one of $s");
		}
		return $this->input;
	}
}