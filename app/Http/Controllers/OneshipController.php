<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportExcelRequest;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\Oneship;
use App\Http\Requests\SearchOneshipRequest;
use App\Http\Requests\UpdateOneshipRequest;
use App\Http\Requests\ExportExcelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\xlsx;

class OneshipController extends Controller
{
    public function index(SearchOneshipRequest $request)
    {
        $search = $request->input('search', '');
        $limit = 1000;

        $query = Oneship::query();
        if (!empty($search)) {
            $query->where('e1_code', 'like', "%$search%");
        }

        $oneships = $query->orderBy('release_date', 'desc')->paginate($limit);
        $totalRows = Oneship::count();
        return view('oneship.index', compact('oneships','totalRows', 'search'));
    }

    public function importExcel(ImportExcelRequest $request)
    {
        $files = $request->file('excelFiles');

        if (!$files || count($files) === 0) {
            return back()->withErrors(["message" => "Vui lòng chọn ít nhất một file Excel để tải lên."]);
        }

        DB::beginTransaction();
        $totalImported = 0;

        foreach ($files as $file) {
            try {
                $reader = IOFactory::createReaderForFile($file->getPathname());
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();

                $headerRow = null;
                for ($row = 1; $row <= 5; $row++) {
                    $cellValue = trim($sheet->getCell("A$row")->getValue());
                    if (preg_match('/^(Mã E1|Ma_E1)$/i', $cellValue)) {
                        $headerRow = $row;
                        break;
                    }
                }
                if (!$headerRow) {
                    return back()->withErrors(["message" => "File '{$file->getClientOriginalName()}' không đúng định dạng."]);
                }

                $columns = [];
                $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $colValue = trim($sheet->getCell("$colLetter$headerRow")->getValue());

                    if (preg_match('/^(Mã E1|Ma_E1)$/i', $colValue)) {
                        $columns['e1_code'] = $col;
                    } elseif (preg_match('/^(Ngày Đóng|Ngay_Phat_Hanh|Ngay_Dong)$/i', $colValue)) {
                        $columns['release_date'] = $col;
                    } elseif (preg_match('/^(Khối lượng|Khoi_Luong|KL_Tinh_Cuoc)$/i', $colValue)) {
                        $columns['chargeable_volumn'] = $col;
                    } elseif (preg_match('/^(Cuoc_E1|Cước E1|Cước Chính|Cuoc_Chinh)$/i', $colValue)) {
                        $columns['main_charge'] = $col;
                    } elseif (preg_match('/^(Nguoi_Nhan|Người Nhận)$/i', $colValue)) {
                        $columns['receiver'] = $col;
                    } elseif (preg_match('/^(DC_Nhan|DCNhan)$/i', $colValue)) {
                        $columns['recipient_address'] = $col;
                    } elseif (preg_match('/^(Dien_Thoai|Dien_Thoai_Nhan)$/i', $colValue)) {
                        $columns['phone_number'] = $col;
                    } elseif (preg_match('/^(So_Tham_Chieu)$/i', $colValue)) {
                        $columns['reference_number'] = $col;
                    }
                }

                if (!isset($columns['e1_code'])) {
                    return back()->withErrors(["message" => "Không tìm thấy cột Mã E1 trong file '{$file->getClientOriginalName()}'"]);
                }

                $insertData = [];
                for ($row = $headerRow + 1; $row <= $sheet->getHighestRow(); $row++) {
                    $e1_code = trim($sheet->getCell(Coordinate::stringFromColumnIndex($columns['e1_code']) . $row)->getValue());
                    if (!preg_match('/^E.*VN$/', $e1_code)) {
                        continue;
                    }

                    $rowData = [
                        'e1_code'           => $e1_code,
                        'release_date'      => isset($columns['release_date']) ? $this->excelDateToPHP($sheet->getCell(Coordinate::stringFromColumnIndex($columns['release_date']) . $row)->getValue()) : null,
                        'chargeable_volumn' => isset($columns['chargeable_volumn']) ? (int)$sheet->getCell(Coordinate::stringFromColumnIndex($columns['chargeable_volumn']) . $row)->getValue() : null,
                        'main_charge'       => isset($columns['main_charge']) ? (int)str_replace(',', '', $sheet->getCell(Coordinate::stringFromColumnIndex($columns['main_charge']) . $row)->getCalculatedValue()) : null,
                        'receiver'          => isset($columns['receiver']) ? trim($sheet->getCell(Coordinate::stringFromColumnIndex($columns['receiver']) . $row)->getValue()) : null,
                        'recipient_address' => isset($columns['recipient_address']) ? trim($sheet->getCell(Coordinate::stringFromColumnIndex($columns['recipient_address']) . $row)->getValue()) : null,
                        'phone_number'      => isset($columns['phone_number']) ? trim($sheet->getCell(Coordinate::stringFromColumnIndex($columns['phone_number']) . $row)->getValue()) : null,
                        'reference_number'  => isset($columns['reference_number']) ? trim($sheet->getCell(Coordinate::stringFromColumnIndex($columns['reference_number']) . $row)->getValue()) : null,
                    ];
                    $insertData[] = $rowData;
                }

                if (!empty($insertData)) {
                    foreach ($insertData as $data) {
                        Oneship::updateOrCreate(['e1_code' => $data['e1_code']], $data);
                    }
                    $totalImported += count($insertData);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(["message" => "Lỗi xử lý file '{$file->getClientOriginalName()}': " . $e->getMessage()]);
            }
        }

        DB::commit();
        return back()->with(['message' => "Đã nhập {$totalImported} dòng thành công"]);
    }

    private function excelDateToPHP($excelDate)
    {
        return is_numeric($excelDate) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate)->format('Y-m-d') : null;
    }

    public function exportExcel(ExportExcelRequest $request)
    {
        try {
            $file = $request->file('file');
            if (!$file) {
                return back()->withErrors(['message' => 'Vui lòng chọn file để xuất dữ liệu!']);
            }

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumnIndex = Coordinate::columnIndexFromString($worksheet->getHighestColumn());

            $colMaE1Index = null;
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $colValue = trim($worksheet->getCell($colLetter . '1')->getValue());

                if (preg_match('/^(Mã E1|Ma_E1)$/i', $colValue)) {
                    $colMaE1Index = $col;
                    break;
                }
            }

            if ($colMaE1Index === null) {
                return back()->withErrors(['message' => 'Không tìm thấy cột chứa Mã E1 trong file Excel.']);
            }

            $colMaE1Letter = Coordinate::stringFromColumnIndex($colMaE1Index);
            $colCuocChinhIndex = $highestColumnIndex + 1;
            $colCuocChinhLetter = Coordinate::stringFromColumnIndex($colCuocChinhIndex);
            $worksheet->setCellValue($colCuocChinhLetter . '1', 'Cuoc_Chinh');

            for ($row = 2; $row <= $highestRow; $row++) {
                $maE1 = trim($worksheet->getCell($colMaE1Letter . $row)->getValue() ?? '');
                if (!empty($maE1)) {
                    $cuocChinh = DB::table('oneships')->where('e1_code', $maE1)->value('main_charge');

                    if (!is_null($cuocChinh)) {
                        $worksheet->setCellValue($colCuocChinhLetter . $row, $cuocChinh);
                    }
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

            return response()->download(storage_path("app/$outputFile"))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Lỗi khi xuất file: ' . $e->getMessage()]);
        }
    }
}
