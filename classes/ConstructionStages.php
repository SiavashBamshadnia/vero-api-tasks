<?php

class ConstructionStages
{
	private $db;

	public function __construct()
	{
		$this->db = Api::getDb();
	}

	public function getAll()
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE status <> 'DELETED'
		");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function post(ConstructionStagesCreate $data)
	{
		$validated = Validation::validate($data, [
			'name' => ['required', 'maxlength:255'],
			'startDate' => ['required', 'iso8601'],
			'endDate' => ['iso8601'],
			'durationUnit' => ['between:HOURS,DAYS,WEEKS', 'default:DAYS'],
			'color' => ['hexcolor'],
			'externalId' => ['maxlength:255'],
			'status' => ['between:NEW,PLANNED,DELETED', 'default:NEW'],
		]);
		$data = $validated;

		$data->duration = $data->getDuration();

		$stmt = $this->db->prepare("
			INSERT INTO construction_stages
			    (name, start_date, end_date, duration, durationUnit, color, externalId, status)
			    VALUES (:name, :start_date, :end_date, :duration, :durationUnit, :color, :externalId, :status)
			");
		$stmt->execute([
			'name' => $data->name,
			'start_date' => $data->startDate,
			'end_date' => $data->endDate,
			'duration' => $data->duration,
			'durationUnit' => $data->durationUnit,
			'color' => $data->color,
			'externalId' => $data->externalId,
			'status' => $data->status,
		]);
		return $this->getSingle($this->db->lastInsertId(), true);
	}

	public function getSingle($id, $withDeleted = false)
	{
		$sql = "
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE ID = :id
		";
		if (!$withDeleted) {
			$sql .= "AND status <> 'DELETED'";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$result)
			throw new NotFoundException();

		return $result;
	}

	/**
	 * This method updates a ConstructionStagesCreate object.
	 * Its url is /constructionStages/{id} and you have to send a PATCH request in order to call this function.
	 */
	public function patch(ConstructionStagesCreate $data, string $id)
	{
		// Validate the input
		$validated = Validation::validate($data, [
			'name' => 'maxlength:255',
			'startDate' => 'iso8601',
			'endDate' => 'iso8601',
			'durationUnit' => 'between:HOURS,DAYS,WEEKS',
			'color' => 'hexcolor',
			'externalId' => 'maxlength:255',
			'status' => 'between:NEW,PLANNED,DELETED',
		]);

		// Convert the $validated object to an associative array
		$validated = get_object_vars($validated);

		$entity = $this->getSingle($id);
		if (!$entity) {
			throw new NotFoundException();
		}
		$entity = new ConstructionStagesCreate($entity);
		$entity->update($validated);

		// Make startDate and endDate snakecase.
		if (isset($validated['startDate'])) {
			$validated['start_date'] = $validated['startDate'];
			unset($validated['startDate']);
		}
		if (isset($validated['endDate'])) {
			$validated['end_date'] = $validated['endDate'];
			unset($validated['endDate']);
		}

		// Calculate the duration
		$validated['duration'] = $entity->duration = $entity->getDuration();

		// Generate the SQL query
		$validatedKeys = array_keys($validated);

		$setFields = [];
		foreach ($validatedKeys as $validatedKey) {
			$setFields[] = "$validatedKey = :$validatedKey";
		}
		$setString = implode(', ', $setFields);

		$sql = "
UPDATE construction_stages SET $setString WHERE id = :id AND status <> 'DELETED'
";
		$params = array_merge(['id' => $id], $validated);

		// Execute the SQL query
		$statement = $this->db->prepare($sql);
		$statement->execute($params);

		return $entity;
	}

	/**$
	 * This method deletes a ConstructionStagesCreate object.
	 * Its url is /constructionStages/{id} and you have to send a DELETE request in order to call this function.
	 */
	public function delete($id)
	{
		$stmt = $this->db->prepare("
			UPDATE construction_stages
			SET status = 'DELETED'
			WHERE ID = :id AND status <> 'DELETED'
		");
		$stmt->execute(['id' => $id]);
	}
}
