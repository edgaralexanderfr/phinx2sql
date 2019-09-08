<?php

namespace Phinx2SQL;

class Phinx2SQL
{
    public static function main($argc, array $argv)
    {
        try {
            $params = self::_getParams($argc, $argv);
            
            if ($argc < 2 
                || self::_checkParam($params, '-h') 
                || self::_checkParam($params, '--help')) {
                self::_printHelp($params);
    
                return;
            }
            
            self::_printMigration($params);
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    }

    private static function _printHelp(array $params)
    {
        echo
            'Phinx2SQL 1.2.0'                                                                                     . PHP_EOL . 
                                                                                                                    PHP_EOL . 
            'Usage:'                                                                                              . PHP_EOL . 
            '  command [migrations path] [arguments]:'                                                            . PHP_EOL . 
                                                                                                                    PHP_EOL . 
            'Options:'                                                                                            . PHP_EOL . 
            '  -h, --help          Display this help message'                                                     . PHP_EOL . 
            '  -m                  Specify migration ID or part of the name of the file'                          . PHP_EOL . 
            '  -g, --up, --down    `-g up` to get the "Migrate Up" or `-g down` to get the "Migrate Down"'        . PHP_EOL . 
            '  --append-semicolon  Add a semicolon to every statement that passes through the `execute` function' . PHP_EOL . 
            '  --colorize          Colorize the MySQL syntax from the output'                                     . PHP_EOL . 
            '';
    }
    
    private static function _printMigration(array $params)
    {
        $migration = self::_getParamValue($params, '-m');

        if (!$migration) {
            throw new \Exception('No migration provided');
        }

        $migrationsPath = self::_getParamElement($params, 0);
        
        if (!$migrationsPath) {
            $migrationsPath = MIGRATIONS_DEFAULT_PATH;
        }

        if (!is_dir($migrationsPath)) {
            throw new \Exception('The provided migrations folder does not exist');
        }

        $found = false;
        $dir = opendir($migrationsPath);

        while (($file = readdir($dir)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            if (strpos($file, $migration) !== false
                && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
                $fileNameArray = explode(
                    '_',
                    pathinfo($file, PATHINFO_FILENAME)
                );

                unset($fileNameArray[0]);

                foreach ($fileNameArray as $i => $fileName) {
                    $fileNameArray[$i] = ucfirst($fileName);
                }

                $class = implode('', $fileNameArray);

                $appendSemicolon
                    = self::_checkParam($params, '--append-semicolon') 
                    ? 'true' : 'false';

                $colorize
                    = self::_checkParam($params, '--colorize') 
                    ? 'true' : 'false';

                $methods = 
                    'private function addColor($text)
                    {
                        return "\033[35m" . $text . "\033[0m";
                    }

                    public function colorize($sql)
                    {
                        $search = array(
                            "AS",
                            "as",
                            "BY",
                            "by",
                            "IF",
                            "if",
                            "IN",
                            "in",
                            "IS",
                            "is",
                            "ON",
                            "on",
                            "OR",
                            "or",
                            "TO",
                            "to",
                            "ADD",
                            "add",
                            "ALL",
                            "all",
                            "AND",
                            "and",
                            "ASC",
                            "asc",
                            "BDB",
                            "bdb",
                            "DEC",
                            "dec",
                            "DIV",
                            "div",
                            "FOR",
                            "for",
                            "INT",
                            "int",
                            "KEY",
                            "key",
                            "MOD",
                            "mod",
                            "NOT",
                            "not",
                            "SET",
                            "set",
                            "SSL",
                            "ssl",
                            "USE",
                            "use",
                            "XOR",
                            "xor",
                            "END",
                            "end",
                            "BLOB",
                            "blob",
                            "BOTH",
                            "both",
                            "CASE",
                            "case",
                            "CHAR",
                            "char",
                            "DESC",
                            "desc",
                            "DROP",
                            "drop",
                            "ELSE",
                            "else",
                            "FROM",
                            "from",
                            "HASH",
                            "hash",
                            "HELP",
                            "help",
                            "INTO",
                            "into",
                            "JOIN",
                            "join",
                            "KEYS",
                            "keys",
                            "KILL",
                            "kill",
                            "LEFT",
                            "left",
                            "LIKE",
                            "like",
                            "LOAD",
                            "load",
                            "LOCK",
                            "lock",
                            "LONG",
                            "long",
                            "NULL",
                            "null",
                            "READ",
                            "read",
                            "REAL",
                            "real",
                            "SHOW",
                            "show",
                            "SOME",
                            "some",
                            "THEN",
                            "then",
                            "TRUE",
                            "true",
                            "WHEN",
                            "when",
                            "WITH",
                            "with",
                            "ALTER",
                            "alter",
                            "BTREE",
                            "btree",
                            "CHECK",
                            "check",
                            "CROSS",
                            "cross",
                            "FALSE",
                            "false",
                            "FLOAT",
                            "float",
                            "FORCE",
                            "force",
                            "GRANT",
                            "grant",
                            "GROUP",
                            "group",
                            "INDEX",
                            "index",
                            "INNER",
                            "inner",
                            "LIMIT",
                            "limit",
                            "LINES",
                            "lines",
                            "MATCH",
                            "match",
                            "ORDER",
                            "order",
                            "OUTER",
                            "outer",
                            "PURGE",
                            "purge",
                            "RIGHT",
                            "right",
                            "RLIKE",
                            "rlike",
                            "RTREE",
                            "rtree",
                            "TABLE",
                            "table",
                            "TYPES",
                            "types",
                            "UNION",
                            "union",
                            "USAGE",
                            "usage",
                            "USING",
                            "using",
                            "WHERE",
                            "where",
                            "WRITE",
                            "write",
                            "BEGIN",
                            "begin",
                            "AFTER",
                            "after",
                            "FIRST",
                            "first",
                            "BIGINT",
                            "bigint",
                            "BINARY",
                            "binary",
                            "CHANGE",
                            "change",
                            "COLUMN",
                            "column",
                            "CREATE",
                            "create",
                            "DELETE",
                            "delete",
                            "DOUBLE",
                            "double",
                            "ERRORS",
                            "errors",
                            "EXISTS",
                            "exists",
                            "FIELDS",
                            "fields",
                            "HAVING",
                            "having",
                            "IGNORE",
                            "ignore",
                            "INFILE",
                            "infile",
                            "INNODB",
                            "innodb",
                            "INSERT",
                            "insert",
                            "OPTION",
                            "option",
                            "REGEXP",
                            "regexp",
                            "RENAME",
                            "rename",
                            "REVOKE",
                            "revoke",
                            "SELECT",
                            "select",
                            "SONAME",
                            "soname",
                            "TABLES",
                            "tables",
                            "UNIQUE",
                            "unique",
                            "UNLOCK",
                            "unlock",
                            "UPDATE",
                            "update",
                            "VALUES",
                            "values",
                            "RETURN",
                            "return",
                            "ANALYZE",
                            "analyze",
                            "BETWEEN",
                            "between",
                            "CASCADE",
                            "cascade",
                            "COLLATE",
                            "collate",
                            "COLUMNS",
                            "columns",
                            "DECIMAL",
                            "decimal",
                            "DEFAULT",
                            "default",
                            "DELAYED",
                            "delayed",
                            "ESCAPED",
                            "escaped",
                            "EXPLAIN",
                            "explain",
                            "FOREIGN",
                            "foreign",
                            "INTEGER",
                            "integer",
                            "LEADING",
                            "leading",
                            "NATURAL",
                            "natural",
                            "NUMERIC",
                            "numeric",
                            "OUTFILE",
                            "outfile",
                            "PRIMARY",
                            "primary",
                            "REPLACE",
                            "replace",
                            "REQUIRE",
                            "require",
                            "RETURNS",
                            "returns",
                            "SPATIAL",
                            "spatial",
                            "STRIPED",
                            "striped",
                            "TINYINT",
                            "tinyint",
                            "VARCHAR",
                            "varchar",
                            "VARYING",
                            "varying",
                            "DECLARE",
                            "declare",
                            "DATABASE",
                            "database",
                            "DAY_HOUR",
                            "day_hour",
                            "DESCRIBE",
                            "describe",
                            "DISTINCT",
                            "distinct",
                            "ENCLOSED",
                            "enclosed",
                            "FULLTEXT",
                            "fulltext",
                            "FUNCTION",
                            "function",
                            "GEOMETRY",
                            "geometry",
                            "INTERVAL",
                            "interval",
                            "LONGBLOB",
                            "longblob",
                            "LONGTEXT",
                            "longtext",
                            "OPTIMIZE",
                            "optimize",
                            "RESTRICT",
                            "restrict",
                            "SMALLINT",
                            "smallint",
                            "STARTING",
                            "starting",
                            "TINYBLOB",
                            "tinyblob",
                            "TINYTEXT",
                            "tinytext",
                            "TRAILING",
                            "trailing",
                            "UNSIGNED",
                            "unsigned",
                            "WARNINGS",
                            "warnings",
                            "ZEROFILL",
                            "zerofill",
                            "CHARACTER",
                            "character",
                            "DATABASES",
                            "databases",
                            "LOCALTIME",
                            "localtime",
                            "MEDIUMINT",
                            "mediumint",
                            "MIDDLEINT",
                            "middleint",
                            "PRECISION",
                            "precision",
                            "PROCEDURE",
                            "procedure",
                            "VARBINARY",
                            "varbinary",
                            "BERKELEYDB",
                            "berkeleydb",
                            "CONSTRAINT",
                            "constraint",
                            "DAY_MINUTE",
                            "day_minute",
                            "DAY_SECOND",
                            "day_second",
                            "MEDIUMBLOB",
                            "mediumblob",
                            "MEDIUMTEXT",
                            "mediumtext",
                            "MRG_MYISAM",
                            "mrg_myisam",
                            "OPTIONALLY",
                            "optionally",
                            "PRIVILEGES",
                            "privileges",
                            "REFERENCES",
                            "references",
                            "TERMINATED",
                            "terminated",
                            "YEAR_MONTH",
                            "year_month",
                            "DISTINCTROW",
                            "distinctrow",
                            "HOUR_MINUTE",
                            "hour_minute",
                            "HOUR_SECOND",
                            "hour_second",
                            "CURRENT_DATE",
                            "current_date",
                            "CURRENT_TIME",
                            "current_time",
                            "LOW_PRIORITY",
                            "low_priority",
                            "VARCHARACTER",
                            "varcharacter",
                            "HIGH_PRIORITY",
                            "high_priority",
                            "MINUTE_SECOND",
                            "minute_second",
                            "STRAIGHT_JOIN",
                            "straight_join",
                            "AUTO_INCREMENT",
                            "auto_increment",
                            "LOCALTIMESTAMP",
                            "localtimestamp",
                            "SQL_BIG_RESULT",
                            "sql_big_result",
                            "USER_RESOURCES",
                            "user_resources",
                            "MASTER_SERVER_ID",
                            "master_server_id",
                            "SQL_SMALL_RESULT",
                            "sql_small_result",
                            "CURRENT_TIMESTAMP",
                            "current_timestamp",
                            "SQL_CALC_FOUND_ROWS",
                            "sql_calc_found_rows"
                        );

                        $strings = explode(" ", $sql);
                        $total   = count($strings);

                        for ($i = 0; $i < $total; $i++) {
                            if (in_array($strings[$i], $search)) {
                                $strings[$i] = $this->addColor($strings[$i]);
                            }
                        }
                        
                        return implode(" ", $strings);
                    }
                    
                    public function execute($sql)
                    {
                        $appendSemicolon = ' . $appendSemicolon . ';
                        $colorize        = ' . $colorize . ';

                        if ($appendSemicolon) {
                            $sql .= ";";
                        }

                        if ($colorize) {
                            $sql = $this->colorize($sql);
                        }
                        
                        echo $sql . PHP_EOL;
                    }';

                $script  = '<?php class AbstractMigration { ' . $methods . ' } ';
                $script .= str_replace(array('<?php', 'use Phinx\Migration;', 'use Phinx\Migration\AbstractMigration;'), '', file_get_contents($migrationsPath . '/' . $file)) . ' ';
                $script .= '$object = new ' . $class . '(); ';

                $migrateUpHeader   = '"-- -' . PHP_EOL . '-- Migrate Up'   . PHP_EOL . '-- -' . PHP_EOL . '"';
                $migrateDownHeader = '"-- -' . PHP_EOL . '-- Migrate Down' . PHP_EOL . '-- -' . PHP_EOL . '"';

                if (self::_checkParam($params, '--colorize')) {
                    $migrateUpHeader   = '"\033[32m" . ' . $migrateUpHeader   . ' . "\033[0m"';
                    $migrateDownHeader = '"\033[32m" . ' . $migrateDownHeader . ' . "\033[0m"';
                }

                if (self::_checkParam($params, '-g', 'up') || self::_checkParam($params, '--up')) {
                    $script .= 'echo ' . $migrateUpHeader . '; $object->up(); ';
                } else {
                    if (self::_checkParam($params, '-g', 'down') || self::_checkParam($params, '--down')) {
                        $script .= 'echo ' . $migrateDownHeader . '; $object->down(); ';
                    } else {
                        $script .= 'echo ' . $migrateUpHeader . '; $object->up(); echo PHP_EOL; echo ' . $migrateDownHeader . '; $object->down(); ';
                    }
                }

                eval('?>' . $script);

                $found = true;

                break;
            }
        }

        closedir($dir);

        if (!$found) {
            throw new \Exception('No migration found');
        }
    }

    private static function _getParams($argc, array $argv)
    {
        $params = array(
            'values'   => array(),
            'elements' => array(),
        );

        if ($argc < 2) {
            return $params;
        }

        $i = 1;
        
        while ($i < $argc) {
            $name = $argv[$i];
            
            if (isset($name[0]) && $name[0] == '-') {
                if (isset($argv[$i + 1]) && (!isset($argv[$i + 1][0]) || $argv[$i + 1][0] != '-')) {
                    $params['values'][$name] = $argv[$i + 1];
                    $i += 2;
                } else {
                    $params['values'][$name] = true;
                    $i++;
                }
            } else {
                $params['elements'][] = $name;
                $i++;
            }
        }

        return $params;
    }

    private static function _checkParam(array $params, $name, $value = true)
    {
        return isset($params['values'][$name])
            && $params['values'][$name] === $value;
    }

    private static function _getParamValue(array $params, $name)
    {
        return isset($params['values'][$name])
            ? $params['values'][$name]
            : null;
    }

    private static function _getParamElement(array $params, $index)
    {
        return isset($params['elements'][$index])
            ? $params['elements'][$index]
            : null;
    }
}
