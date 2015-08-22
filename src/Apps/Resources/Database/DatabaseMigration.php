<?php
namespace Apps\Resources\Database;

use Cygnite\Console\Generator\MigrationReset;
/**
 * Class DatabaseMigration
 *
 * @package Apps\Resources\Database
 */
class DatabaseMigration extends MigrationReset
{
    /**
     * Specify all migration file name here
     * MigrationResetCommand will rollback all changes
     *
     * If you specify 'reset' => 'all', Migration Command will reset
     * all migrations files it won't care files your specified into
     * the list.
     *
     * You can also specify 'reset' => '', and provide file names into
     *
     * 'files' => [
     *   '20150820185938_my_product_table',
     *   '20150820193015_my_demo_table'
     * ]
     *
     * Example Command:
     * <code>
     *
     * php cygnite migration:reset all
     *
     * php cygnite migration:reset
     *
     * </code>
     *
     * It will rollback specified migrations
     *
     * @var array
     */
    protected $migrations = [
        /*
         | command => 'reset' //or up or refresh
         */
        'command' => 'reset',
        'files' => [
            '20150820185938_mytable',
            '20150820193015_my_demo_table'
        ]
    ];

    /**
     * Execute all migrations
     */
    public function run()
    {
        $this->execute();
    }
}
