<?php

use PhpCsFixer\Config;

return Config::create()
    ->setRiskyAllowed(false)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => true,
        'combine_consecutive_unsets' => true,
        'ordered_class_elements' => true,
        'hash_to_slash_comment' => true,
        'header_comment' => false,

        // Useless blocks:
        'no_closing_tag' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_extra_consecutive_blank_lines' => [
            'break',
            'continue',
            'extra',
            'return',
            'throw',
            'use',
            'parenthesis_brace_block',
            'square_brace_block',
            'curly_brace_block',
        ],
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,

        // PHPDoc:
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_separation' => true,
        'phpdoc_order' => true,

        'php_unit_fqcn_annotation' => true,
    ]);
