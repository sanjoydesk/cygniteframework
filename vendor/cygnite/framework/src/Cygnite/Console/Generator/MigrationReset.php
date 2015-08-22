<?php
/*
 * This file is part of the Cygnite package.
 *
 * (c) Sanjoy Dey <dey.sanjoy0@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cygnite\Console\Generator;

/**
 * Class MigrationReset
 *
 * @package Cygnite\Console\Generator
 */
class MigrationReset
{
    const MIGRATION_DIR = '/Resources/Database/Migrations/';

    /**
     * Get all files names and command type to migrate
     *
     * @return mixed
     */
    public function getMigrations()
    { echo "3........";
        return $this->migrations;
    }

    protected function execute()
    {

    }


}
