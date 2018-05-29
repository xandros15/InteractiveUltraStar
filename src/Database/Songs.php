<?php


namespace UltraStar\Database;


use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\Driver\Exception\InvalidArgumentException;

class Songs
{
    private const COLLECTION_NAME = 'songs';
    static private $default = [
        'name' => '',
        'artists' => [],
        'languages' => [],
        'tags' => [],
        'createdAt' => null,
        'updatedAt' => null,
    ];
    /** @var Collection */
    private $songs;

    /**
     * Songs constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->songs = $database->selectCollection(self::COLLECTION_NAME);
    }

    /**
     * @param array $params
     *
     * @return string inserted Id
     */
    public function create(array $params): string
    {
        $params = array_merge(static::$default, $params);
        $params['createdAt'] = new UTCDateTime();

        $insert = $this->songs->insertOne($params);
        if (!$insert->getInsertedCount()) {
            throw new RuntimeException("Can't add new record");
        }

        return $insert->getInsertedId();
    }

    /**
     * @param string $id
     *
     * @return array|null|object
     */
    public function read(string $id)
    {
        try {
            $id = new ObjectId($id);
        } catch (InvalidArgumentException $exception) {
            throw new DocumentNotFoundException((string) $id);
        }
        $song = $this->songs->findOne(['_id' => $id]);
        if (!$song) {
            throw new DocumentNotFoundException((string) $id);
        }

        return $song;
    }

    /**
     * @param string $id
     * @param array $params
     *
     * @return string upserted id
     */
    public function update(string $id, array $params): string
    {
        try {
            $id = new ObjectId($id);
        } catch (InvalidArgumentException $exception) {
            throw new DocumentNotFoundException((string) $id);
        }
        $update = $this->songs->updateOne(['_id' => $id], [
            '$set' => $params,
            '$currentDate' => [
                'updatedAt' => true,
            ],
        ]);
        if (!$update->getModifiedCount()) {
            throw new RuntimeException("No documents changed. Id: {$id}");
        }

        return $id;
    }

    /**
     * @param string $id
     */
    public function delete(string $id)
    {
        try {
            $id = new ObjectId($id);
        } catch (InvalidArgumentException $exception) {
            throw new DocumentNotFoundException((string) $id);
        }
        $delete = $this->songs->deleteOne(['_id' => $id]);
        if (!$delete->getDeletedCount()) {
            throw new DocumentNotFoundException((string) $id);
        }
    }

    /**
     * @param array $query
     * @param array $options
     *
     * @return \MongoDB\Driver\Cursor
     */
    public function find(array $query = [], array $options = [])
    {
        $options = array_merge(['limit' => 50], $options);
        $songs = $this->songs->find($query, $options);

        return $songs;
    }
}
