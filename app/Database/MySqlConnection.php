<?php

namespace App\Database;

use Illuminate\Database\MySqlConnection as BaseMySqlConnection;
use App\Database\Schema\MySqlBuilder;

class MySqlConnection extends BaseMySqlConnection
{
    /**
     * Get a schema builder instance for the connection.
     * Override untuk menggunakan custom MySqlBuilder yang kompatibel dengan MySQL versi lama
     *
     * @return \App\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new MySqlBuilder($this);
    }
}
