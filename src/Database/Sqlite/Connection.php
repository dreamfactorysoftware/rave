<?php
namespace DreamFactory\Core\Database\Sqlite;

use DreamFactory\Managed\Support\Managed;

/**
 * Connection represents a connection to a Sqlite database.
 */
class Connection extends \DreamFactory\Core\Database\Connection
{
    public $initSQLs = ['PRAGMA foreign_keys=1'];

    public static function checkRequirements($driver, $throw_exception = true)
    {
        if (!extension_loaded('sqlite3')) {
            if ($throw_exception) {
                throw new \Exception("Required extension 'sqlite3' is not installed or loaded.");
            } else {
                return false;
            }
        }

        return parent::checkRequirements('sqlite', $throw_exception);
    }

    public static function getDriverLabel()
    {
        return 'SQLite';
    }

    public static function getSampleDsn()
    {
        // http://php.net/manual/en/ref.pdo-sqlite.connection.php
        return 'sqlite:db.sq3';
    }

    public function __construct($dsn = '', $username = '', $password = '')
    {
        $file = substr($dsn, 7);
        if (false === strpos($file, DIRECTORY_SEPARATOR)) {
            // no directories involved, store it where we want to store it
            if (config('df.standalone')) {
                $storage = config('df.db.sqlite_storage');
            } else {
                $storage = Managed::getStoragePath();
                $storage = rtrim($storage, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'databases';
            }
            if (is_dir($storage)) {
                $dsn = 'sqlite:' . rtrim($storage, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            }
        }

        parent::__construct($dsn, $username, $password);
    }

    public function getSchema()
    {
        if ($this->schema !== null) {
            return $this->schema;
        }

        return new Schema($this);
    }
}