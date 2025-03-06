<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        '@PSR12' => true,
        '@Symfony' => true,
        '@PHP82Migration' => true,
    ])
    ->setFinder($finder)
    ;
