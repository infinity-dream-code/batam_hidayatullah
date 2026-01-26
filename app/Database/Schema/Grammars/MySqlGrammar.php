<?php

namespace App\Database\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as BaseMySqlGrammar;

class MySqlGrammar extends BaseMySqlGrammar
{
    /**
     * Compile the query to determine the tables.
     * Override untuk kompatibilitas dengan MySQL versi lama yang tidak punya information_schema
     *
     * @param  string  $database
     * @return string
     */
    public function compileTables($database)
    {
        // Untuk MySQL versi lama, gunakan SHOW TABLES sebagai fallback
        // Tapi tetap coba information_schema dulu jika tersedia
        return sprintf(
            'select table_name as `name`, (data_length + index_length) as `size`, '
            .'table_comment as `comment`, engine as `engine`, table_collation as `collation` '
            ."from information_schema.tables where table_schema = %s and table_type in ('BASE TABLE', 'SYSTEM VERSIONED') "
            .'order by table_name',
            $this->quoteString($database)
        );
    }
}
