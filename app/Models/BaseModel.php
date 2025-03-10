<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected $table; // Table name should be set in child models

    /**
     * Fetch records with conditions.
     */
    public function getData(array $conditions = [], array $select = ['*'], array $orderBy = [], $limit = null, $offset = null)
    {
        $query = DB::table($this->table)->select($select);
        $query = $this->applyConditions($query, $conditions);

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        if (!is_null($offset)) {
            $query->offset($offset);
        }

        return $query->get();
    }

    /**
     * Fetch records with joins.
     */
    public function get_join(array $conditions = [], array $joins = [], array $select = ['*'], array $orderBy = [], $limit = null, $offset = null)
    {
        $query = DB::table($this->table)->select($select);
        $query = $this->applyConditions($query, $conditions);

        foreach ($joins as $join) {
            $query->join($join['table'], $join['on'][0], $join['on'][1], $join['on'][2]);
        }

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        if (!is_null($offset)) {
            $query->offset($offset);
        }

        return $query->get();
    }

    /**
     * Add a single record.
     */
    public function add(array $data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     * Add multiple records in batch.
     */
    public function add_batch(array $data)
    {
        return DB::table($this->table)->insert($data);
    }

    /**
     * Update record(s) with conditions.
     */
    public function update_record(array $conditions, array $data)
    {
        $query = DB::table($this->table);
        $query = $this->applyConditions($query, $conditions);
        return $query->update($data);
    }

    /**
     * Delete record(s) with conditions.
     */
    public function delete_record(array $conditions)
    {
        $query = DB::table($this->table);
        $query = $this->applyConditions($query, $conditions);
        return $query->delete();
    }

    /**
     * Apply all conditions dynamically.
     */
    protected function applyConditions($query, array $conditions)
    {
        foreach ($conditions as $key => $condition) {
            if (is_array($condition)) {
                // Handling operators like where, orWhere, whereNot, whereIn, etc.
                switch (strtolower($condition[0])) {
                    case 'or':
                        $query->orWhere($condition[1], $condition[2], $condition[3]);
                        break;
                    case 'not':
                        $query->whereNot($condition[1], $condition[2], $condition[3]);
                        break;
                    case 'in':
                        $query->whereIn($condition[1], $condition[2]);
                        break;
                    case 'notin':
                        $query->whereNotIn($condition[1], $condition[2]);
                        break;
                    case 'between':
                        $query->whereBetween($condition[1], $condition[2]);
                        break;
                    case 'notbetween':
                        $query->whereNotBetween($condition[1], $condition[2]);
                        break;
                    default:
                        $query->where($condition[0], $condition[1], $condition[2]);
                        break;
                }
            } else {
                $query->where($key, $condition);
            }
        }
        return $query;
    }

    /**
     * Get column values as an array.
     */
    public function array_column($column, array $conditions = [])
    {
        $query = DB::table($this->table)->select($column);
        $query = $this->applyConditions($query, $conditions);
        return $query->pluck($column)->toArray();
    }
}
