<?php

class ParserScriptShell
{
    /**
     * The character to denote a comment in sh file
     */
    const COMMENT_CHAR = '#';

    /**
     * The sign of assigning a value to a variable in sh file
     */
    const ASSIGNMENTS_CHAR = '=';

    /**
     * @var array - Variables from sh file
     */
    private $variables;

    /**
     * ParserScriptShell constructor.
     * @param string $fileName - The name of the shell script file
     */
    function __construct(string $fileName)
    {
        $this->setDefinedVariablesInCode($this->getCodeLinesWithoutComments($fileName));
    }

    /**
     * Get code from file, remove comments from lines, return code lines as array
     * @param string $scriptFilePath
     * @return array
     */
    private function getCodeLinesWithoutComments(string $scriptFilePath): array
    {
        $codeLines = [];
        if ($file = fopen($scriptFilePath, "r")) {
            while (!feof($file)) {
                // get file line by line
                $line = fgets($file);

                if (substr($line, 0, 1) === self::COMMENT_CHAR) {
                    // if line is comments
                    continue;
                }

                // comment inside the line
                $commentCharNumber = strpos($line, ' ' . self::COMMENT_CHAR);
                if ($commentCharNumber !== false) {
                    // cut all char after comment
                    $line = substr($line, 0, $commentCharNumber);
                }

                if (empty(trim($line))) {
                    // empty line
                    continue;
                }

                $codeLines[] = $line;
            }
            fclose($file);
        }

        return $codeLines;
    }

    /**
     * Looks for assigning a value to a variable, save result to $variables
     * @param array $codeLines
     */
    private function setDefinedVariablesInCode(array $codeLines): void
    {
        foreach ($codeLines as $codeLine) {
            $commandsInLine = explode(' ', $codeLine);
            foreach ($commandsInLine as $command) {
                $command = trim($command);
                $assignmentCharNumber = strpos($command, self::ASSIGNMENTS_CHAR);
                // if this is the command which contain assign a value to a variable
                if ($assignmentCharNumber !== false) {
                    // cut name variables
                    $variableName = substr($command, 0, $assignmentCharNumber);
                    // cut name value
                    $variableValue = substr($command, $assignmentCharNumber + 1);
                    $this->variables[$variableName] = $this->parseValue($variableValue);
                }
            }
        };
    }

    /**
     * Parses the values that contain the variables
     * @param $variableValue
     * @return string
     */
    private function parseValue(string $variableValue): string
    {
        if (is_array($this->variables)) {
            foreach ($this->variables as $name => $value) {
                if (strpos($variableValue, '$' . $name) !== false) {
                    // exchange variable in value
                    $variableValue = str_replace('$' . $name, $value, $variableValue);
                }
            }
        }

        return $variableValue;
    }

    /**
     * Returns the value of a variable
     * @param string $variableName
     * @return string
     */
    public function getVariable(string $variableName): string
    {
        $variableValue = '';

        if (isset($this->variables[$variableName])) {
            $variableValue = $this->variables[$variableName];
        }

        return $variableValue;
    }

}
