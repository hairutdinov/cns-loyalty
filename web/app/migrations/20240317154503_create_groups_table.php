<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('groups');
        $table->addColumn('name', 'string')
            ->create();
    }
}
