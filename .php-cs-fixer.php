<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(
        [
            'vendor',
        ]
    );

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->registerCustomFixers([])
    ->setRules(
        [
            '@Symfony' => true,
            'strict_param' => false,
            'array_syntax' => ['syntax' => 'short'],
            'concat_space' => ['spacing' => 'one'],
            'phpdoc_align' => ['align' => 'left'],
            'phpdoc_summary' => false,
            'void_return' => false,
            'phpdoc_var_without_name' => false,
            'phpdoc_to_comment' => false,
            'single_line_throw' => false,
            'modernize_types_casting' => true,
            'function_declaration' => false,
            'ordered_imports' => [
                'imports_order' => [
                    'class',
                    'function',
                    'const',
                ],
                'sort_algorithm' => 'alpha',
            ],
        ]
    )
    ->setRiskyAllowed(true);
