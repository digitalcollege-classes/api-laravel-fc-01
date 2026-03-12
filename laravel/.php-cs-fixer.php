<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in([__DIR__])
    ->exclude(['vendor', 'storage', 'bootstrap/cache']) // Pastas comuns para ignorar no Laravel
    ->notPath('*.blade.php') // Evita tentar formatar arquivos Blade
    ->notName('_ide_helper*.php') // Ignora arquivos mágicos gerados pelo laravel-ide-helper
    ->notName('.phpstorm.meta.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

// 2. Retorne a configuração com as regras
return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS2.0' => true, // Padrão moderno (substituto do PSR-12)
        '@PhpCsFixer' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false], // Padrão Laravel (sem Yoda)
        'array_syntax' => ['syntax' => 'short'], // Uso de colchetes [] em vez de array()
        'binary_operator_spaces' => [
            'default' => 'single_space', // Alinhamento padrão e limpo (ex: sem espaços gigantes = )
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'match', 'parameters']], // Vírgulas finais
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'concat_space' => ['spacing' => 'one'],
        'single_quote' => true,
        'declare_strict_types' => true, // Mantém a padronização forte
        'visibility_required' => [
            'elements' => ['property', 'method', 'const']
        ],
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try']
        ],
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'remove_inheritdoc' => true,
        ],
    ])
    ->setFinder($finder);
