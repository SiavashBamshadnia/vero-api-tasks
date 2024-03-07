<?php

/**
 * Abstract class Validator
 *
 * Provides the base functionality for creating validators.
 * This abstract class defines the structure and methods that all validators must implement.
 *
 */
abstract class Validator
{
	/**
	 * @var bool Determines if the validation should stop on the first failure.
	 */
	public bool $cascadeOnFailure = false;
	/**
	 * @var string The name of the input field being validated.
	 */
	protected string $name;
	/**
	 * @var string|null The input value to be validated.
	 */
	protected string|null $input;

	/**
	 * Constructor for the Validator.
	 *
	 * Initializes the validator with a name and an optional input value.
	 *
	 * @param string $name The name of the input field being validated.
	 * @param string|null $input The input value to be validated.
	 */
	public function __construct(string $name, string|null $input)
	{
		$this->name = $name;
		$this->input = $input;
	}

	/**
	 * Validates the input according to the specific validation rules defined by the subclass.
	 *
	 * This method is abstract and must be implemented by subclasses.
	 * It should return the validated input value or throw an error if the input is invalid.
	 *
	 * @return mixed The validated input value.
	 * @throws ValidationException If the input is invalid.
	 */
	abstract public function validate(): mixed;
}
