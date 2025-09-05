<?php
declare(strict_types = 1);

$finder = PhpCsFixer\Finder::create()
    ->in(['./src', './migrations', './tests'])
    ->notContains('readonly class EncryptService')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHP73Migration' => true,
        '@PHPUnit75Migration:risky' => true,
        'yoda_style' => false,
        'blank_line_after_opening_tag' => false,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'return',
                'throw',
                'try',
                'if',
            ],
        ],
        'phpdoc_no_empty_return' => false,
        'mb_str_functions' => true,
        'simplified_null_return' => true,
        'static_lambda' => true,
        'ordered_interfaces' => true,
        'phpdoc_to_param_type' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_test_case_static_method_calls' => false,
        'native_function_invocation' => false,
        'linebreak_after_opening_tag' => false,
        'phpdoc_to_return_type' => true,
        'phpdoc_to_comment' => false,
        'declare_equal_normalize' => ['space' => 'single'],
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'doctrine_annotation_braces' => ['syntax' => 'with_braces'],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author',
                'created',
                'version',
                'package',
                'copyright',
                'license',
                'throws',
            ],
        ],
        'nullable_type_declaration_for_default_null_value' => false,
    ])
    ->setFinder($finder)
;
