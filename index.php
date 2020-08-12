<?php
/**
 * Task: Write a program that resolves and prints out variables defined in a script.
 * The input file is "script.sh", and the variable that we expect to find and resolve is "$CLASSPATH"
 *
 * Notes: The expected output from any program is:
 * /opt/bea/wlserver6.1:/opt/bea/wlserver6.1/lib/weblogic_sp.jar:/opt/bea/wlserver6.1/lib/weblogic.jar:/home/BSS1/uif/jar/log4j.jar:/home/BSS1/uif/jar/tibrvj.jar:/home/BSS1/uif/jar/Maverick4.jar:/home/BSS1/uif/jar/TIBRepoClient4.jar:/home/BSS1/uif/jar/dom4j-full-1.1.jar:/home/BSS1/uif/cc/cfg
 *
 * Alternative input files may be used in the final reckoning.
 */

require_once('class/ParserScriptShell.class.php');

const SCRIPT_FILE_NAME = 'script.sh';

$parser = new ParserScriptShell(SCRIPT_FILE_NAME);
echo $parser->getVariable('CLASSPATH');
