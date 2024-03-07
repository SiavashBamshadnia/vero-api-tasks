<?php

/**
 * Class ISO8601
 *
 * A specialized validator that checks if the input represents a date and time in ISO 8601 format.
 *
 */
class ISO8601 extends Validator
{
	/**
	 * Validates the input to ensure it represents a date and time in ISO 8601 format.
	 *
	 * This method checks if the input matches the ISO 8601 datetime format (i.e. 2022-12-31T14:59:00Z).
	 * If the input does not match the ISO 8601 format a ValidationException is thrown.
	 *
	 * @return string|null The validated input value if it is a valid ISO 8601 datetime string, or null if the input was null.
	 * @throws ValidationException If the input is not null and does not represent a valid ISO 8601 datetime string.
	 */
	public function validate(): string|null
	{
		if (is_null($this->input)) {
			return null;
		}

		$exception = new ValidationException("Invalid date format");

		$pattern = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(.\d+)?([+-]\d{2}:\d{2}|Z)?$/';

		// Check if the datetime format is valid
		if (preg_match($pattern, $this->input, $matches) !== 1) {
			throw $exception;
		}

		// Extract matched components
		list(, $year, $month, $day, $hour, $minute, $second) = $matches;

		// Check if components are valid
		if (!checkdate((int)$month, (int)$day, (int)$year) || $hour >= 24 || $minute >= 60 || $second >= 60) {
			throw $exception;
		}

		return $this->input;
	}
}