<?php

/**
 * Split Delivery plugin for Craft CMS 3.x
 *
 * Delivery Splitting plugin for Commerce
 *
 * @link      https://digilab.co.uk
 * @copyright Copyright (c) 2023 Dene Hewings
 */

namespace digilab\split\migrations;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    digiLab
 * @package   Split Delivery
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    protected $tableName = '{{%digilab_split_delivery}}';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema($this->tableName);
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                $this->tableName,
                [
                    'id'          => $this->primaryKey(),
                    'orderId'     => $this->integer()->notNull(),
                    'productId'     => $this->integer()->notNull(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid'         => $this->uid(),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes()
    {

        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'orderId',
                false
            ),
            $this->tableName,
            'orderId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'productId',
                false
            ),
            $this->tableName,
            'productId',
            false
        );
        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * @return void
     */
    protected function addForeignKeys()
    {

        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'orderId'),
            $this->tableName,
            'orderId',
            '{{%commerce_orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'productId'),
            $this->tableName,
            'productId',
            '{{%commerce_products}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists($this->tableName);
    }
}
