<?php
namespace DreamFactory\Core\Components;

use DreamFactory\Core\Contracts\DbSchemaInterface;
use DreamFactory\Core\Exceptions\InternalServerErrorException;
use Illuminate\Database\ConnectionInterface;

/**
 * Database Schema Extension Manager
 */
class DbSchemaExtensions
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The database schema extension resolvers.
     *
     * @var array
     */
    protected $extensions = [];

    //*************************************************************************
    //	Methods
    //*************************************************************************

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Register a schema extension resolver.
     *
     * @param string   $name
     * @param callable $extension
     */
    public function extend($name, callable $extension)
    {
        $this->extensions[$name] = $extension;
    }

    /**
     * Return the schema extension object.
     *
     * @param string              $name
     *
     * @param ConnectionInterface $conn
     *
     * @return DbSchemaInterface
     */
    public function getSchemaExtension($name, ConnectionInterface $conn)
    {
        if (isset($this->extensions[$name])) {
            return call_user_func($this->extensions[$name], $conn);
        } else if (!isset($this->extensions[$name]) && $name === 'sqlsrv') {
            throw new InternalServerErrorException('SQL Server support is only available in DreamFactory\'s commercial version. Please visit www.dreamfactory.com to schedule a call with our sales team');
        }

        return null;
    }
}
