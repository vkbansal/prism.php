<?php
namespace VKBansal\Prism\Languages\Definitions;

use VKBansal\Prism\Languages\AbstractLanguage;

class Ruby extends AbstractLanguage
{
    public function definition()
    {
        return $this->extend('clike', [
            'comment'=> "/\#[^\r\n]*(\r?\n|$)/",
            'keyword'=> "/\b(alias|and|BEGIN|begin|break|case|class|def|define_method|defined|do|each|else|elsif|END|end|ensure|false|for|if|in|module|new|next|nil|not|or|raise|redo|require|rescue|retry|return|self|super|then|throw|true|undef|unless|until|when|while|yield)\b/",
            'builtin'=> "/\b(Array|Bignum|Binding|Class|Continuation|Dir|Exception|FalseClass|File|Stat|File|Fixnum|Fload|Hash|Integer|IO|MatchData|Method|Module|NilClass|Numeric|Object|Proc|Range|Regexp|String|Struct|TMS|Symbol|ThreadGroup|Thread|Time|TrueClass)\b/",
            'constant'=> "/\b[A-Z][a-zA-Z_0-9]*[?!]?\b/"
        ]);
    }

    public function setup()
    {
        $this->insertBefore('ruby', [
            'regex'=> [
                "pattern"=> "/(^|[^\/])\/(?!\/)(\[.+?]|\\\\.|[^\/\r\n])+\/[gim]{0,3}(?=\s*($|[\r\n,.;})]))/",
                "lookbehind"=> true
            ],
            'variable'=> "/[@$]+\b[a-zA-Z_][a-zA-Z_0-9]*[?!]?\b/",
            'symbol'=> "/:\b[a-zA-Z_][a-zA-Z_0-9]*[?!]?\b/"
        ], 'keyword');
    }
}
