<?php

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Contra;

Artisan::command('contras', function (CodeGenerationServiceInterface $codeGenerationService) {
    Contra::whereNotNull('voucher_no')->update([
        'voucher_no' => null,
    ]);

    Contra::first()->update(['voucher_no' => $codeGenerationService->generate(table: 'contras', column: 'voucher_no', prefix: 'CON', suffixSeparator: '-')]);

    $contras = Contra::all()->skip(1);
    foreach ($contras as $contra) {
        $contra->update(['voucher_no' => $codeGenerationService->generate(table: 'contras', column: 'voucher_no', prefix: 'CON', suffixSeparator: '-')]);
    }
});
