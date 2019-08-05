<?php

use Phinx\Migration;

class Test extends AbstractMigration
{
    public function up()
    {
        $this->execute("INSERT INTO test (name) VALUES ('foo');");
        $this->execute("UPDATE test SET name = 'bar' WHERE name = 'foo';");
    }

    public function down()
    {
        $this->execute("UPDATE test SET name = 'foo' WHERE name = 'bar';");
        $this->execute("DELETE FROM test WHERE name = 'foo';");
    }
}
