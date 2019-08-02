<?php

namespace Phinx\Migration;

class AbstractMigration
{
    public function execute($sql)
    {
        echo $sql . PHP_EOL;
    }
}
