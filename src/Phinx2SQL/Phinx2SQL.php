<?php

namespace Phinx2SQL;

class Phinx2SQL
{
    public static function main($argc, array $argv)
    {
        try {
            self::_printMigration(self::_getParams($argc, $argv));
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
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

                $class  = implode('', $fileNameArray);

                $script  = '<?php class AbstractMigration { public $p2s_isUp = true; public function execute($sql) { echo "-- -" . PHP_EOL; if ($this->p2s_isUp) { echo "-- Migrate Up" . PHP_EOL; } else { echo "-- Migrate Down" . PHP_EOL; } echo "-- -" . PHP_EOL; echo $sql . PHP_EOL; } } ';
                $script .= str_replace(array('<?php', 'use Phinx\Migration;', 'use Phinx\Migration\AbstractMigration;'), '', file_get_contents($migrationsPath . '/' . $file)) . ' ';
                $script .= '$object = new ' . $class . '(); ';

                if (self::_checkParam($params, '-g', 'up') || self::_checkParam($params, '--up')) {
                    $script .= '$object->up(); ';
                } else {
                    if (self::_checkParam($params, '-g', 'down') || self::_checkParam($params, '--down')) {
                        $script .= '$object->p2s_isUp = false; $object->down(); ';
                    } else {
                        $script .= '$object->up(); echo PHP_EOL; $object->p2s_isUp = false; $object->down(); ';
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
