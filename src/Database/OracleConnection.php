<?php

namespace DreamFactory\Core\Database;

use DreamFactory\Core\Contracts\ConnectionInterface;
use DreamFactory\Core\Database\Oci\Schema as OciSchema;

class OracleConnection extends \Yajra\Oci8\Oci8Connection implements ConnectionInterface
{
    use ConnectionExtension;

    public $pdoClass = 'DreamFactory\Core\Database\Oci\PdoAdapter';

    public static function checkRequirements()
    {
        if (!extension_loaded('oci8')) {
            throw new \Exception("Required extension 'oci8' is not detected, but may be compiled in.");
        }
        // don't call parent method here, no need for PDO driver
    }

    public static function getDriverLabel()
    {
        return 'Oracle Database';
    }

    public static function getSampleDsn()
    {
        // http://php.net/manual/en/ref.pdo-oci.connection.php
        return 'oci:dbname=(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.1)(PORT = 1521))) (CONNECT_DATA = (SID = db)))';
    }

    public function getSchema()
    {
        if ($this->schemaExtension === null) {
            $this->schemaExtension = new OciSchema($this);
        }

        return $this->schemaExtension;
    }
}
