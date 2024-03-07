<?php

class Validation
{
	/**
	 * Validates data against a set of rules.
	 *
	 * This method iterates over the provided data and applies validation rules defined in the $items array.
	 * It supports various validation types such as 'between', 'default', 'hexcolor', 'iso8601', 'maxlength', and 'required'.
	 * If validation fails for any item, it collects error messages and optionally stops further validation based on the cascadeOnFailure flag.
	 *
	 * @param ConstructionStagesCreate $data The data object to be validated.
	 * @param array $items An associative array where keys are the data object properties to be validated and values are the validation rules.
	 * @return ConstructionStagesCreate The validated data object with only validated properties.
	 * @throws Error If a validation rule is invalid or an unexpected validation rule is encountered.
	 * @throws ValidationException If validation fails for a data item.
	 */
	public static function validate(ConstructionStagesCreate $data, array $items): ConstructionStagesCreate
	{
		// Clone the data object to avoid modifying the original object.
		$data = clone $data;

		$errors = [];
		foreach ($items as $key => $validators) {
			// Check if the key exists in the data object, if not, set it to null in order to prevent exceptions.
			if (!isset($data->$key)) {
				$data->$key = null;
			}

			if (!is_array($validators)) {
				$validators = [$validators];
			}

			// Iterate over each validation rule for the current key.
			foreach ($validators as $validatorItem) {
				// Split the validation rule into parts.
				$arr = explode(':', $validatorItem);

				// Check if the validation rule format is valid.
				if (!in_array(count($arr), [1, 2])) {
					throw new Error("Invalid validation rule for $key");
				}

				// If there are additional parameters, split them.
				if (count($arr) === 2) {
					$validatorParams = explode(',', $arr[1]);
				} else {
					$validatorParams = [];
				}

				// Determine the validator class based on the validation rule type.
				$validatorClass = match ($arr[0]) {
					'between' => Between::class,
					'default' => _Default::class,
					'hexcolor' => HexColor::class,
					'iso8601' => ISO8601::class,
					'maxlength' => MaxLength::class,
					'required' => Required::class,
					default => throw new Error("Unexpected validation rule '{$arr[0]}'"),
				};

				$validator = new $validatorClass($key, $data->$key, ...$validatorParams);

				// Validate the input and update the data object if valid and collect validation errors.
				try {
					$data->$key = $validator->validate();
				} catch (ValidationException $e) {
					$errors[$key][] = $e->getMessage();
					if ($validator->cascadeOnFailure) {
						break;
					}
				}
			}
		}

		// Remove any properties from the data object that were not validated.
		foreach ($data as $key => $value) {
			if (is_null($value)) {
				unset($data->$key);
			}
		}

		$itemsKeys = array_keys($items);
		$array = get_object_vars($data);
		foreach ($array as $key => $value) {
			if (!in_array($key, $itemsKeys)) {
				unset($data->$key);
			}
		}

		// If there were validation errors, return a proper response
		if (count($errors) > 0) {
			http_response_code(400);
			die(json_encode([
				'errors' => $errors,
			]));
		}

		return $data;
	}
}