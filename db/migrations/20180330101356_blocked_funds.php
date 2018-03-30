<?php


use Phinx\Migration\AbstractMigration;

class BlockedFunds extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('blocked_funds');

        $table->addColumn('user_from_id', 'integer')
            ->addForeignKey('user_from_id', 'users', 'id', [
                'delete'=> 'NO_ACTION', 'update'=> 'Cascade'
            ])
            ->addColumn('funds', 'integer')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('blocked_funds');
    }
}
