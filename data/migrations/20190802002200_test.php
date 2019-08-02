<?php

use Phinx\Migration;

class Test extends AbstractMigration
{
    public function up()
    {
        $this->execute("INSERT INTO test (name) VALUES ('foo');");
    }

    public function down()
    {
        $this->execute("DELETE FROM test WHERE name = 'foo';");
    }
}
