<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PSR12'       => true,
    'array_syntax' => [
        'syntax' => 'short'
    ],
    'ordered_imports'   => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'no_useless_else'   => true,
    'no_useless_return' => true,
    'trailing_comma_in_multiline' => true,

    'no_empty_statement' => true,

    'no_whitespace_in_blank_line' => true,
    'standardize_not_equals'      => true,
    'combine_consecutive_unsets'  => true,
    'concat_space'                => ['spacing' => 'one'],
    'array_indentation'           => true,
    'unary_operator_spaces'       => true,
    'blank_line_before_statement' => [
        'statements' => [
            'case',
            'declare',
            'return',
            'throw',
            'try'
        ],
    ],
    'binary_operator_spaces' => [
        'default'   => 'align_single_space',
        'operators' => [
            '=>' => 'align_single_space_minimal'
        ],
    ],
    'phpdoc_var_without_name' => true,
    'method_argument_space'   => [
        'on_multiline'                     => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => true,
    ],
    'align_multiline_comment' => [
        'comment_type' => 'phpdocs_only',
    ],
    'lowercase_cast'                     => true,
    'lowercase_static_reference'         => true,
    'general_phpdoc_tag_rename'          => true,
    'phpdoc_inline_tag_normalizer'       => true,
    'phpdoc_tag_type'                    => true,
    'phpdoc_no_empty_return'             => true,
    'phpdoc_trim'                        => true,
    'phpdoc_scalar'                      => true,
    'no_blank_lines_after_class_opening' => true,
    'phpdoc_separation'                  => false,
    'phpdoc_single_line_var_spacing'     => true,
    'phpdoc_indent'                      => true,
    'no_superfluous_phpdoc_tags'         => false,
    'phpdoc_align'                       => [
        'align' => 'vertical',
        'tags'  => [
            'param', 'throws', 'type', 'var', 'property'
        ]
    ],
];

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder);

