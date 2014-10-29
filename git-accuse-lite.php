#!/usr/bin/php
<?
 /**
 * Git Accuse
 */

class GitAccuse
{
 const VERSION = '0.001';
 
 public function __construct()
 {


 }


 public function main($filename, $line, $comment=false)
 {
    

     if ($filename == '--version') return self::VERSION;
  
 }


}


$g = new GitAccuse();

echo $g->main($argv[1], $argv[2], $argv[3]) . "\n";
