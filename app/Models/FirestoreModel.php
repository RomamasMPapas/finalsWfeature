<?php
namespace App\Models;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Collection;

/**
 * Base class for simple Firestore CRUD operations.
 * Child classes only need to define the collection name.
 */
class FirestoreModel
{
    protected $db;
    protected $collection;

    public function __construct(string $collection)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(config('firebase.service_account'));
        $this->db = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->createFirestore()
            ->database();
        $this->collection = $collection;
    }

    /** Get all documents as a Laravel collection */
    public function all(): Collection
    {
        $documents = $this->db->collection($this->collection)->documents();
        $items = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $items[] = $data;
            }
        }
        return collect($items);
    }

    public function find($id)
    {
        $doc = $this->db->collection($this->collection)->document((string) $id);
        return $doc->exists() ? array_merge(['id' => $doc->id()], $doc->data()) : null;
    }

    public function create(array $attributes)
    {
        $docRef = $this->db->collection($this->collection)->add($attributes);
        return $docRef->id();
    }

    public function update($id, array $attributes)
    {
        $this->db->collection($this->collection)
                 ->document((string) $id)
                 ->set($attributes, ['merge' => true]);
    }

    public function delete($id)
    {
        $this->db->collection($this->collection)
                 ->document((string) $id)
                 ->delete();
    }

    /** Simple where = value filter (Firestore only supports = for simple queries) */
    public function where(string $field, $value)
    {
        $documents = $this->db->collection($this->collection)
                              ->where($field, '=', $value)
                              ->documents();
        $items = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $items[] = $data;
            }
        }
        return collect($items);
    }
}
?>
