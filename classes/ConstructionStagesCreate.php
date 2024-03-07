<?php

class ConstructionStagesCreate
{
	public $name;
	public $startDate;
	public $endDate;
	public $duration;
	public $durationUnit;
	public $color;
	public $externalId;
	public $status;

	public function __construct($data)
	{
		if (is_object($data)) {
			$data = get_object_vars($data);
		}

		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Calculates the duration between the start and end dates of a construction stage.
	 *
	 * This method first checks if the end date is null. If it is, the method returns null, indicating that the duration cannot be calculated.
	 * Otherwise, it creates DateTime objects for both the start and end dates and calculates the difference based on the specified duration unit.
	 * The method supports calculating durations in hours, days, and weeks.
	 *
	 * @return int|null The duration between the start and end dates, in the specified unit, or null if the end date is null.
	 */
	public function getDuration(): int|null
	{
		if (is_null($this->endDate)) {
			return null;
		}

		$startDate = new DateTime($this->startDate);
		$endDate = new DateTime($this->endDate);

		return match ($this->durationUnit) {
			'HOURS' => $startDate->diff($endDate)->h + $startDate->diff($endDate)->days * 24,
			'WEEKS' => $startDate->diff($endDate)->days / 7,
			default => $startDate->diff($endDate)->days,
		};
	}

	public function update($entity)
	{
		if (is_object($entity)) {
			$entity = get_object_vars($entity);
		}

		foreach ($entity as $key => $value) {
			$this->$key = $value;
		}
	}
}