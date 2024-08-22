<?php

namespace clie;

use SilverStripe\ORM\DataObject;
use Silverstripe\Core\ClassInfo;
use SilverStripe\CMS\Model\SiteTree;

class Consoler
{
    private static $instance = null;
    private $accumulator = [];

    private function __construct()
    {
        // Private constructor to prevent direct instantiation
    }

    public static function getInstance(): Consoler
    {
        if (self::$instance === null) {
            self::$instance = new Consoler();
        }
        return self::$instance;
    }

    public static function log($string)
    {
        self::getInstance()->accumulator[] = $string;
    }

    public static function dump()
    {
        return array_reduce(self::getInstance()->accumulator, function ($acc, $obj) {
            if (is_array($obj)) {
                return $acc .= "console.log(`" . var_export($obj, true) . "`);\n";
            }

            if (in_array(DataObject::class, ClassInfo::ancestry($obj))) {
                return $acc .= self::buildObjectOutput($obj);
            }

            return $acc .= "console.log('$obj');\n";
        }, "");
    }

    public static function buildObjectOutput($object)
    {
        return join("\n", [
            "console.group('$object::class');",

            "console.group('Fields');",
            "console.log(`" . var_export($object->getQueriedDatabaseFields(), true) . "`);",
            'console.groupEnd();',

            "console.group('Belongs to');",
            "console.log(`" . var_export($object->belongsTo(), true) . "`);",
            'console.groupEnd();',

            "console.group('Has one');",
            "console.log(`" . var_export($object->hasOne(), true) . "`);",
            'console.groupEnd();',

            "console.group('Has Many');",
            "console.log(`" . var_export($object->hasMany(), true) . "`);",
            'console.groupEnd();',

            "console.group('Many many');",
            "console.log(`" . var_export($object->manyMany(), true) . "`);",
            'console.groupEnd();',

            'console.groupEnd();',
        ]);
    }
}
