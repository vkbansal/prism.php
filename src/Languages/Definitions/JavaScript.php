<?php
namespace VKBansal\Prism\Languages\Definitions;

use VKBansal\Prism\Languages\AbstractLanguage;

class JavaScript extends AbstractLanguage
{
    public function definition()
    { 
        return $this->extend('clike', [
            'keyword' => "/\b(break|case|catch|class|const|continue|debugger|default|delete|do|else|enum|export|extends|false|finally|for|function|get|if|implements|import|in|instanceof|interface|let|new|null|package|private|protected|public|return|set|static|super|switch|this|throw|true|try|typeof|var|void|while|with|yield)\b/",
            "number" => "/\b-?(0x[\dA-Fa-f]+|\d*\.?\d+([Ee]-?\d+)?|NaN|-?Infinity)\b/"
        ]);
    }

    public function setup()
    {
        $this->insertBefore('javascript', [
            'regex'=> [
                "pattern"=> "/(^|[^\/])\/(?!\/)(\[.+?]|\\\\.|[^\/\r\n])+\/[gim]{0,3}(?=\s*($|[\r\n,.;})]))/",
                "lookbehind"=> true
            ]
        ], 'keyword');
        
        $markup = $this->repository->hasDefinition('markup');
        
        if ($markup) {           
            $inside = $this->repository->getDefinition('markup.tag.inside');

            $this->insertBefore('markup', [
                "script"=> [
                    "pattern"=> "/<script[\w\W]*?>[\w\W]*?<\/script>/i",
                    "inside"=> [
                        'tag'=> [
                            "pattern"=> "/<script[\w\W]*?>|<\/script>/i",
                            "inside"=> $inside
                        ],
                        "rest" => $this->definition()
                    ],
                    "alias"=> 'language-javascript'
                ]
            ], 'tag');
        }
    }
}

