<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportExcelRequest;
use App\Http\Services\ExcelImportedService;

class ImportController extends Controller
{


    public function __construct(private ExcelImportedService $importedService)
    {
        // $this->importedService = $importedService;
    }

    public function importExcel(ImportExcelRequest $request)
    {
        $files = $request->file('excelFiles');
        $result = $this->importedService->importExcelFiles($files);
        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', $result['success']);
    }
}
