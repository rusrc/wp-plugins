<?php

namespace C4lPartners\Repositories;

use C4lPartners\Models\IBaseModel;

class BaseRepository
{
    /**
     * Insert or update entity
     * 
     * @param IBaseModel $model
     * @return int primary id 
     */
    public function InsertOrUpdateItem(IBaseModel $model)
    {
        global $wpdb;

        // Get the model's properties
        $props = $this->properties($model);

        // Flatten complex objects
        $props = $this->flatten_props($props);

        $insertedId = 0;
        // Insert or update?

        if (is_null($model->getPrimaryKey())) {

            $wpdb->insert($model->getTableName(), $props);
            $insertedId = $wpdb->insert_id;
        } else {
            $wpdb->update(
                $model->getTableName(), // Table name
                /**
                 * what to update:
                 * 'meta_value' => 'new value'
                 */
                $props,
                /**
                 * where:
                 * 'id' => 1,
                 * 'meta_key' => 'first_name'
                 */
                array('id' => $model->getPrimaryKey())
            );
        }

        return $insertedId;
    }

    public function DeleteItem(IBaseModel $model)
    {
        global $wpdb;

        $rows = $wpdb->delete(
            $model->getTableName(),
            array('id' => $model->getPrimaryKey())
        );

        return $rows;
    }

    private function properties(IBaseModel $model)
    {
        return get_object_vars($model);
    }

    private function flatten_props($props)
    {
        foreach ($props as $property => $value) {
            if (is_object($value) && get_class($value) == 'DateTime') {
                $props[$property] = $value->format('Y-m-d H:i:s');
            } elseif (is_array($value)) {
                $props[$property] = serialize($value);
            } elseif ($value instanceof AbstractClass) {
                $props[$property] = $value->primary_key();
            }
        }

        return $props;
    }
}
