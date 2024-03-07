<?php

/**
 * Class HexColor
 *
 * A specialized validator that checks if the input represents a color in hexadecimal format.
 *
 */
class HexColor extends Validator
{
	/**
	 * Validates the input to ensure it represents a color in hexadecimal format.
	 *
	 * This method checks if the input is a valid hex color code (either 3 or 6 characters long, starting with a '#').
	 * If the input does not match the hex color format, a ValidationException is thrown.
	 *
	 * @return string|null The validated input value if it is a valid hex color code, or null if the input was null.
	 * @throws ValidationException If the input is not null and does not represent a valid hex color code.
	 */
	public function validate(): string|null
	{
		if (!is_null($this->input) && !preg_match('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $this->input)) {
			throw new ValidationException("This field must represent a color in the hexadecimal format");
		}
		return $this->input;
	}
}