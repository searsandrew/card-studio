<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Storage;

class Hook
{
    protected static mixed $instance;

    public static function run(string $name)
    {
        $hookName = sprintf('do%sAction', ucfirst($name));
        $plugins = Storage::directories('plugin');

        foreach($plugins as $plugin)
        {
            $classPath = storage_path('app/' . $plugin . '/' . basename($plugin) . '.php');
            if (file_exists($classPath)) {
                require_once $classPath;
            }
            if (class_exists(basename($plugin))) {
                $className = basename($plugin);
                $instance = new $className();

                if(method_exists($instance, $hookName))
                {
                    $instance->$hookName();
                }
            } else {
                throw new \Exception('Class not found');
            }
        }
    }
}
