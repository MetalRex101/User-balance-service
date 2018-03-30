<?php

namespace Tests;

use App\ServiceProvider;
use Dotenv\Dotenv;
use Phinx\Config\Config;
use Phinx\Console\PhinxApplication;
use Phinx\Migration\Manager;
use Pimple\Container;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var PhinxApplication
     */
    protected $phinx;

    /**
     * @throws \Exception
     */
    public function setUp()
    {
        (new Dotenv(__DIR__ . '/../'))->load();

        putenv('APP_ENV=testing');

        $this->container = new Container();
        $this->container->register(new ServiceProvider());

        $this->connection = $this->container['PDOConnection'];

        $this->phinx = $this->getPhinxManager();
        $this->phinx->rollback(getenv('APP_ENV'));
        $this->phinx->migrate(getenv('APP_ENV'));
    }

    protected function withoutCommit(callable $function)
    {
        $this->connection->beginTransaction();

        $function();

        $this->connection->rollBack();
    }

    private function getPhinxManager()
    {
        $config = new Config([
            'paths' => [
                'migrations' => __DIR__.'/../db/migrations',
            ],
            'environments' => [
                'testing' => [
                    'adapter' => 'mysql',
                    'host' => getenv('TEST_DB_HOST'),
                    'name' => getenv('TEST_DB_NAME'),
                    'user' => getenv('TEST_DB_USER'),
                    'pass' => getenv('TEST_DB_PASS'),
                    'port' => getenv('TEST_DB_PORT'),
                ]
            ]
        ]);

        return new Manager($config, new StringInput(' '), new NullOutput());
    }
}
