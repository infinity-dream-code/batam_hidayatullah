<?php

namespace App\Database\Schema;

use Illuminate\Database\Schema\MySqlBuilder as BaseMySqlBuilder;
use Illuminate\Database\QueryException;

class MySqlBuilder extends BaseMySqlBuilder
{
    /**
     * Get the tables for the database.
     * Override untuk kompatibilitas dengan MySQL versi lama yang tidak punya information_schema
     *
     * @return array
     */
    public function getTables()
    {
        try {
            // Coba menggunakan method parent (information_schema)
            return parent::getTables();
        } catch (QueryException $e) {
            // Jika error karena information_schema tidak ada, gunakan SHOW TABLES
            if (str_contains($e->getMessage(), 'information_schema') || 
                str_contains($e->getMessage(), "doesn't exist")) {
                
                $tables = $this->connection->select('SHOW TABLES');
                $databaseName = $this->connection->getDatabaseName();
                $tableKey = 'Tables_in_' . $databaseName;
                
                $result = [];
                foreach ($tables as $table) {
                    $tableName = $table->$tableKey ?? reset((array) $table);
                    $result[] = [
                        'name' => $tableName,
                        'size' => 0,
                        'comment' => '',
                        'engine' => '',
                        'collation' => '',
                    ];
                }
                
                return $result;
            }
            
            // Jika error lain, throw kembali
            throw $e;
        }
    }
}
