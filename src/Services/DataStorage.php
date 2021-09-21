<?php

namespace Peresmishnyk\Task\Services;

class DataStorage
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->initDb();
    }

    protected function initDb()
    {
        $this->pdo->query(
            <<<SQL
CREATE TABLE IF NOT EXISTS "calls" (
  "uuid" text NOT NULL,
  "customerId" integer NOT NULL,
  "datetime" text NOT NULL,
  "duration" integer NOT NULL,
  "phone" text NOT NULL,
  "ip" text NOT NULL,
  "phoneContinent" text,
  "ipContinent" text
);
CREATE INDEX "main"."main"
ON "calls" (
  "uuid" ASC,
  "customerId" ASC
);
SQL
        );
    }

    public function addRow($uuid, $customerId, $datetime, $duration, $phone, $ip)
    {
        $sql = "INSERT INTO calls(uuid, customerId, datetime, duration, phone, ip) VALUES(:uuid, :customerId, :datetime, :duration, :phone, :ip)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'uuid' => $uuid,
            'customerId' => $customerId,
            'datetime' => $datetime,
            'duration' => $duration,
            'phone' => $phone,
            'ip' => $ip,
        ]);
    }

    public function clear($uuid)
    {
        $sql = "DELETE FROM calls WHERE uuid = :uuid";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'uuid' => $uuid
        ]);
    }

    public function updateContinents($rowid, $phoneContinent, $ipContinent)
    {
        $sql = "UPDATE calls SET phoneContinent=:phoneContinent, ipContinent=:ipContinent WHERE rowid = :rowid";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute([
            'rowid' => $rowid,
            'phoneContinent' => $phoneContinent,
            'ipContinent' => $ipContinent
        ]);
    }

    public function getUncompleted()
    {
        $sql = "SELECT rowid, phone, ip FROM calls WHERE phoneContinent IS NULL or ipContinent IS NULL";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getReady($uuid)
    {
        $sql = "SELECT COUNT(rowid) FROM calls WHERE uuid=:uuid";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'uuid' => $uuid
        ]);
        $total = (int)$statement->fetchColumn();

        $sql = "SELECT COUNT(rowid) FROM calls WHERE uuid=:uuid AND phoneContinent IS NOT NULL AND ipContinent IS NOT NULL";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'uuid' => $uuid
        ]);
        $ready = (int)$statement->fetchColumn();
        return $total != 0 ? $ready / $total : false;
    }

    public function getReports()
    {
        $sql = <<<SQL
SELECT DISTINCT
        ( uuid ) as uuid
FROM
	calls c 
WHERE
	NOT EXISTS ( SELECT * FROM calls c2 WHERE c2.uuid = c.uuid AND ( c2.phoneContinent IS NULL OR c2.ipContinent IS NULL ) );
SQL;
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getReport($uuid)
    {
        $sql = <<<SQL
SELECT
	c.customerId,
	SUM( c.duration ) AS totalDuration,
	count( * ) AS totalCount,
	(
	SELECT
		SUM( c2.duration ) 
	FROM
		calls c2 
	WHERE
		c2.uuid = c.uuid 
		AND c2.phoneContinent = c2.ipContinent 
		AND c2.customerId = c.customerId 
	) AS sameDuration,
	(
	SELECT
		count( * ) 
	FROM
		calls c3 
	WHERE
		c3.uuid = c.uuid 
		AND c3.phoneContinent = c3.ipContinent 
		AND c3.customerId = c.customerId 
	) AS sameCount 
FROM
	calls c 
WHERE
	uuid = :uuid 
GROUP BY
	customerId;
SQL;
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'uuid' => $uuid
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}