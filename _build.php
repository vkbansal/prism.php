<?php

require __DIR__."/../prism.php/vendor/autoload.php";
require __DIR__."/vendor/autoload.php";

use League\CLImate\CLImate;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use VKBansal\FrontMatter\Parser;
use VKBansal\Prism\Components\Plugin;
use VKBansal\Prism\Prism;

$console = new CLImate();

$prism = new Prism();
$prism->loadAllDefinitions();
$prism->loadDefinitions(['php', 'css', 'javascript']);

$loader = new Twig_Loader_Filesystem(__DIR__.'/_templates');
$twig = new Twig_Environment($loader);

$mark = new ParsedownExtra();

$yml = new Finder();
$yml->files()->in(__DIR__."/_data")->name("*.yml");

$data = [];
foreach ($yml as $file) {
    $data[$file->getBasename('.yml')] = Yaml::parse(file_get_contents($file->getRealPath()));
}

libxml_use_internal_errors(true);

/**************************************************************
 First level pages
 *************************************************************/
$pages = new Finder();
$pages->files()->in(__DIR__."/_pages/")->depth("== 0")->name("*.md");

foreach ($pages as $page) {
    $base = $page->getBasename(".md");
    $content = file_get_contents($page->getRealpath());
    $txt = $mark->text($content);
    $txt = $prism->highlightHTML($txt);
    $txt = $twig->render('page.twig', ['content'=> $txt, 'active' => 'introduction', 'data' => $data]);
    $console->out("<light_green>Building</light_green> <bold><light_cyan>{$base}<light_cyan></bold>");
    
    if ($base == "index") {
        file_put_contents(__DIR__.'/index.html', $txt);
    } else {
        if (!is_dir(__DIR__."/{$base}/")) {
            mkdir(__DIR__."/{$base}/");
        }
        file_put_contents(__DIR__."/{$base}/index.html", $txt);
    }
}

/**************************************************************
 * Plugin pages
 *************************************************************/
$finder = new Finder();
$finder->sortByName()->files()->name('*.md')->in(__DIR__."/_pages/plugins");

foreach ($finder as $file) {
    $name = $file->getBasename('.md');
    $prism->resetPlugins();
    $console->out("<light_green>Building</light_green> <bold><light_cyan>{$name} plugin<light_cyan></bold>");
    switch($name){
        case 'show-language':
            $prism->addPlugin(new Plugin\ShowLanguage);
            break;
        case 'line-numbers':
            $prism->addPlugin(new Plugin\LineNumbers);
            break;
        case 'show-invisibles':
            $prism->addPlugin(new Plugin\ShowInvisibles);
            break;
    }
    $path = __DIR__."/plugins/{$name}";
    if(!is_dir($path)){
        mkdir($path, 0655);
    }
    $txt = file_get_contents($file->getRealpath());
    $txt = $mark->text($txt);
    $txt = $twig->render('page.twig', ['content' => $txt, 'active' => $name, 'data' => $data]);
    $txt = $prism->highlightHTML($txt);
    file_put_contents($path. '/index.html', $txt);
}

/**************************************************************
 * Code Samples page
 *************************************************************/
$finder = new Finder();
$finder->sortByName()->files()->name('*.txt')->in(__DIR__.'/_data/code/');
foreach ($finder as $file) {
    $code = file_get_contents($file->getRealpath());
    $lang = $file->getBasename('.txt');
    $console->out("<light_green>Building</light_green> <bold><light_cyan>{$lang}<light_cyan></bold>");
    $doc = Parser::parse($code);
    $meta = $doc->getConfig();
    $code = $prism->highlightText($doc->getContent(), $lang);
    $txt = $twig->render('code-partial.twig', ['meta'=> $meta, 'code' => $code]);
    file_put_contents(__DIR__."/samples/partials/{$lang}.html", $txt);
    $data[] = $meta;
}

$console->out("<light_green>Building</light_green> <bold><light_cyan>Sample Pages<light_cyan></bold>");
$txt = $twig->render('samples.twig', ['active' => 'samples', 'data' => $data]);
file_put_contents(__DIR__."/samples/index.html", $txt);



libxml_use_internal_errors(false);
