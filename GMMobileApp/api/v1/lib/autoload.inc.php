<?php
spl_autoload_register(function ($class_name) {
  $file = __DIR__ . '/cls/' . str_replace("\\", "/", $class_name) . '.php';
  if(is_file($file)) {
    include $file;
  }
});
