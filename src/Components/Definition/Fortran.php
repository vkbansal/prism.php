<?php
namespace VKBansal\Spectrum\Components\Definition;

use VKBansal\Spectrum\Language\AbstractDefinition;

/**
 * Fortran definition
 * @package VKBansal\Spectrum\Definition\Fortran
 * @version 0.3.0
 * @author Vivek Kumar Bansal <contact@vkbansal.me>
 * @license MIT
 */
class Fortran extends AbstractDefinition
{
    /**
     * {@inheritdoc}
     */
    protected static $name = 'fortran';

    /**
     * {@inheritdoc}
     */
    public function definition()
    {
        return [
            'quoted-number' => [
                "pattern" => "/[BOZ](['\"])[A-F0-9]+\g{1}/i",
                "alias" => 'number'
            ],
            'string'=> [
                "pattern" => "/(?:\w+_)?(['\"])(?:\g{1}\g{1}|&\\n(?:\\s*!.+\\n)?|(?!\g{1}).)*(?:\g{1}|&)/",
                "inside" => [
                    'comment' => "/!.*/"
                ]
            ],
            'comment' => "/!.*/",
            'boolean' => "/\.(?:TRUE|FALSE)\.(?:_\w+)?/i",
            'number' => "/(?:\b|[+-])(?:\d+(?:\.\d*)?|\.\d+)(?:[ED][+-]?\d+)?(?:_\w+)?/i",
            'keyword' => [
                // Types
                "/\b(?:INTEGER|REAL|DOUBLE ?PRECISION|COMPLEX|CHARACTER|LOGICAL)\b/i",
                // Statements
                "/\b(?:ALLOCATABLE|ALLOCATE|BACKSPACE|CALL|CASE|CLOSE|COMMON|CONTAINS|CONTINUE|CYCLE|DATA|DEALLOCATE|DIMENSION|DO|END|EQUIVALENCE|EXIT|EXTERNAL|FORMAT|GO ?TO|IMPLICIT(?: NONE)?|INQUIRE|INTENT|INTRINSIC|MODULE PROCEDURE|NAMELIST|NULLIFY|OPEN|OPTIONAL|PARAMETER|POINTER|PRINT|PRIVATE|PUBLIC|READ|RETURN|REWIND|SAVE|SELECT|STOP|TARGET|WHILE|WRITE)\b/i",
                // END statements
                "/\b(?:END ?)?(?:BLOCK ?DATA|DO|FILE|FORALL|FUNCTION|IF|INTERFACE|MODULE|PROGRAM|SELECT|SUBROUTINE|TYPE|WHERE)\b/i",
                // Others
                "/\b(?:ASSIGNMENT|DEFAULT|ELEMENTAL|ELSE|ELSEWHERE|ELSEIF|ENTRY|IN|INCLUDE|INOUT|KIND|NULL|ONLY|OPERATOR|OUT|PURE|RECURSIVE|RESULT|SEQUENCE|STAT|THEN|USE)\b/i"
            ],
            'operator' => [
                "/\*\*|\/\/|=>|[=\/]=|[<>]=?|::|[+\-*=%]|\.(?:EQ|NE|LT|LE|GT|GE|NOT|AND|OR|EQV|NEQV)\.|\.[A-Z]+\./i",
                [
                    // Use lookbehind to prevent confusion with (/ /)
                    "pattern" => "/(^|(?!\().)\/(?!\))/",
                    "lookbehind" => true
                ]
            ],
            'punctuation' => "/\(\/|\/\)|[(),;:&]/"
        ];
    }
}
