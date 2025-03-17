<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class ExcelExportedService
{
    public function export($file)
    {
        try {
            if (!$file) {
                throw new \Exception('Vui lòng chọn file để xuất dữ liệu!');
            }

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumnIndex = Coordinate::columnIndexFromString($worksheet->getHighestColumn());
            $colE1Index = null;
            $dataChange = null;
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $colValue = trim($worksheet->getCell($colLetter . '1')->getValue());

                if (in_array($colValue, ['Mã E1', 'Ma_E1', 'Số hiệu'])) { 
                    $colE1Index = $col;
                    $dataChange = $colValue;
                    break;
                }
            }

            if ($colE1Index === null) {
                throw new \Exception('Không tìm thấy cột chứa mã trong file Excel.');
            }

            $colE1Letter = Coordinate::stringFromColumnIndex($colE1Index);
            $colMainChargeIndex = $highestColumnIndex + 1;
            $colMainChargeLetter = Coordinate::stringFromColumnIndex($colMainChargeIndex);
            $worksheet->setCellValue($colMainChargeLetter . '1', 'Cuoc_Chinh');

            for ($row = 2; $row <= $highestRow; $row++) {
                $e1_code = trim($worksheet->getCell($colE1Letter . $row)->getValue() ?? '');
                if (!empty($e1_code)) {
                    if ($dataChange === 'Số hiệu'){
                    $main_charge = DB::table('vnpost')->where('e1_code', $e1_code)->value('main_charge');
                    }
                    else{
                    $main_charge = DB::table('oneships')->where('e1_code', $e1_code)->value('main_charge');
                    }
                    $worksheet->setCellValue($colMainChargeLetter . $row, $main_charge ?? '');
                }
            }

            if (!Storage::exists('exports')) {
                Storage::makeDirectory('exports');
            }

            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $timestamp = now()->format('Ymd_His');
            $outputFile = "exports/{$fileName}_{$timestamp}.xlsx";

            $writer = new Xlsx($spreadsheet);
            Storage::put($outputFile, '');
            $writer->save(storage_path("app/$outputFile"));

            return storage_path("app/$outputFile");
        } catch (\Exception $e) {
            throw new \Exception('Lỗi khi xuất file: ' . $e->getMessage());
        }
    }
}
