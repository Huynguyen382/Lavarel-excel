<?php

namespace App\Http\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Oneship;
use App\Models\vnpostModel;
use Carbon\Carbon;
use Exception;

class ExcelImportedService
{
    protected array $columnMappings = [
        'e1_code' => ['Mã E1', 'Ma_E1', 'Số hiệu'],
        'release_date' => ['Ngày phát hành', 'Ngay_Phat_Hanh', 'Ngay_Dong', 'Ngày gửi'],
        'chargeable_volumn' => ['Khối Lượng', 'KL_Tinh_Cuoc', 'Khoi_Luong', 'Trọng lượng'],
        'main_charge' => ['Cuoc_E1', 'Cước E1', 'Cước Chính', 'Cuoc_Chinh', 'Tổng cước'],
        'receiver' => ['Nguoi_Nhan', 'Người Nhận'],
        'recipient_address' => ['DC_Nhan', 'Địa chỉ nhận', 'DCNhan', 'Địa chỉ'],
        'phone_number' => ['Dien_Thoai', 'Dien_Thoai_Nhan', 'Điện thoại'],
        'reference_number' => ['So_Tham_Chieu', 'Số đơn hàng'],
    ];
    private ?string $matchData = null;
    private function createDateTime($date): bool|Carbon|null
    {
        $formats = ['d/m/y',  'd/m/Y', 'd-m-y', 'd-m-Y'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date);
            } catch (Exception $e) {
                continue;
            }
        }

        return Carbon::now();
    }
    public function importExcelFiles(array $file): array
    {

        if (!$file || count($file) === 0) {
            return ['error' => 'Vui lòng chọn ít nhất một file Excel để tải lên.'];
        }
        DB::beginTransaction();
        $totalImported = 0;
        foreach ($file as $file) {
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $headerRow = $this->findTitleColumns($sheet);
                if (!$headerRow) {
                    return ['error' => "File '{$file->getClientOriginalName()}'Không tìm thấy dòng tiêu đề trong file Excel."];
                }
                $columns = $this->mapColumns($sheet, $headerRow);
                if (!isset($columns['e1_code'])) {
                    return ['error' => "Không tìm thấy cột mã trong file '{$file->getClientOriginalName()}'"];
                }

                $insertData = $this->extractData($sheet, $columns, $headerRow, $file);
                if (!empty($insertData)) {
                    foreach ($insertData as $data) {
                        if ($this->matchData === 'vnpost') {
                            vnpostModel::updateOrCreate(['e1_code' => $data['e1_code']], $data);
                            continue;
                        } else {
                            Oneship::updateOrCreate(['e1_code' => $data['e1_code']], $data);
                        }
                    }
                    $totalImported += count($insertData);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return ['error' => "Lỗi xử lý file '{$file->getClientOriginalName()}': " . $e->getMessage()];
            }
        }
        DB::commit();
        return ['success' => "Đã nhập {$totalImported} dòng thành công!"];
    }
    private function findTitleColumns($sheet)
    {
        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($row = 1; $row <= 5; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $cellValue = trim((string)$sheet->getCell("{$colLetter}{$row}")->getValue());

                if (empty($cellValue)) {
                    continue;
                }
                $normalizedE1Codes = array_map(fn($item) => preg_replace('/[\x00-\x1F\x7F]/u', '', trim($item)), $this->columnMappings['e1_code']);
                if (in_array($cellValue, $normalizedE1Codes, true)) {
                    return $row;
                }
            }
        }
        return null;
    }
    private function mapColumns($sheet, int $headerRow): array
    {
        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $columns = [];
        $this->matchData = null;
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $colValue = trim($sheet->getCell("$colLetter$headerRow")->getValue());
            foreach ($this->columnMappings as $key => $patterns) {
                $safePattens = array_map('preg_quote', $patterns);
                $regex = '/^(' . implode('|', $safePattens) . ')$/i';
                if (preg_match($regex, $colValue)) {
                    $columns[$key] = $colLetter;

                    if ($key === 'e1_code' && $colValue === 'Số hiệu') {
                        $this->matchData = 'vnpost';
                    }
                    break;
                }
            }
        }
        return $columns;
    }
    private function extractData($sheet, array $column, int $headerRow, $file): array
    {
        $insertData = [];
        for ($row = $headerRow + 1; $row <= $sheet->getHighestRow(); $row++) {
            $e1_code = trim($sheet->getCell($column['e1_code'] . $row)->getValue());
            if (!preg_match('/^E.*VN$/', $e1_code)) {
                continue;
            }

            $insertData[] = [
                'e1_code' => $e1_code,
                'release_date' => $this->createDateTime(trim($sheet->getCell($column['release_date'] . $row)->getValue())),

                'chargeable_volumn' => trim($sheet->getCell($column['chargeable_volumn'] . $row)->getValue()),
                'main_charge' => trim($sheet->getCell($column['main_charge'] . $row)->getValue()),
                'receiver' => isset($column['receiver']) ? trim($sheet->getCell($column['receiver'] . $row)->getValue()) : null,
                'recipient_address' => isset($column['recipient_address']) ? trim($sheet->getCell($column['recipient_address'] . $row)->getValue()) : null,
                'phone_number' => isset($column['phone_number']) ? trim($sheet->getCell($column['phone_number'] . $row)->getValue()) : null,
                'reference_number' => isset($column['reference_number']) ? trim($sheet->getCell($column['reference_number'] . $row)->getValue()) : null,
                'file_name' => $file->getClientOriginalName(),
            ];
        }
        return $insertData;
    }
}
