<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportExcelRequest;
use App\Http\Services\ExcelExportedService;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{

    private ExcelExportedService $excelExportService;

    public function __construct( ExcelExportedService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    
    }
    public function exportExcel(ExportExcelRequest $request)
    {
        try {
            $file = $request->file('file');
            $filePath = $this->excelExportService->export($file);

            return Response::download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
