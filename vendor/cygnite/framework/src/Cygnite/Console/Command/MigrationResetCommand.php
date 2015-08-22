<?php
/*
 * This file is part of the Cygnite package.
 *
 * (c) Sanjoy Dey <dey.sanjoy0@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cygnite\Console\Command;

use Cygnite\Helpers\Inflector;
use Cygnite\Database\Table\Table;
use Cygnite\Console\Command\Command;
use Cygnite\Console\Generator\Migrator;
use Cygnite\Console\Generator\MigrationReset;
use Apps\Resources\Database\DatabaseMigration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Cygnite Migration Reset Command
 *
 * Migration Command class will take care of your entire
 * database migrations using Cygnite CLI.
 *
 * @author Sanjoy Dey <dey.sanjoy0@gmail.com>
 *
 */
class MigrationResetCommand extends Command
{
    protected $name = 'migration:reset';

    protected $description = 'Migrate Entire Database By Cygnite CLI';

    public $table;

    public $migrationReset;

    public $migrator;

    /**
     * @param Table $table
     * @throws \InvalidArgumentException
     */
    public function __construct(Table $table, DatabaseMigration $migration)
    {
        parent::__construct();

        if (!$table instanceof Table) {
            throw new \InvalidArgumentException(sprintf('Constructor parameter should be instance of %s.', $table));
        }

        $this->table = $table;
        $this->migrationReset = $migration;
        $this->migrator = Migrator::instance($this);
    }

    /**
     * @return Table
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * Add parameters to your console command
     *
     */
    protected function configure()
    {
        $this->addArgument('type', null, InputArgument::OPTIONAL, '');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInput($input)->setOutput($output);

        // Migrate init - to create migration table
        $type = $input->getArgument('type');
        $this->processMigrations($type);


        $this->info("Migration completed Successfully!");
    }

    public function processMigrations($type = '')
    {
        if ($type == 'all') {
            //$this->migrationReset->getMigrations();
            $files = $this->migrator->files($this->getMigrationDirPath());
            //$this->migrator->setMigrationClassName();
            show($files);
        }

        show($this->migrationReset->getMigrations());
    }

    /**
     * Get Migration directory path
     *
     * @return string
     */
    public function getMigrationDirPath()
    {
        return realpath(CYGNITE_BASE.DS.APPPATH.MigrationReset::MIGRATION_DIR);
    }
}
