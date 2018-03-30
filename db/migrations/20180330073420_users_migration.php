<?php

use Phinx\Migration\AbstractMigration;

class UsersMigration extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('users');

        $table->addColumn('name', 'string')
            ->addColumn('funds', 'integer')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
