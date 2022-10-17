<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['user'] = $this->db->query("SELECT * FROM user")->getResultArray();
        $data['hobi'] = $this->db->query("SELECT * FROM hobi")->getResultArray();
        $data['tim'] = $this->db->query("SELECT * FROM tim")->getResultArray();

        return view('welcome_message', $data);
    }

    public function excell()
    {
        $user = $this->db->query("SELECT user.user_id, user.user_name, user.user_address FROM user");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'DAFTAR HOBI');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:D2')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama');
        $sheet->setCellValue('C2', 'Alamat');
        $sheet->setCellValue('D2', 'Hobi');

        $no = 1;
        $x = 3;
        foreach ($user->getResult() as $u) :
            $sheet->setCellValue('A' . $x, $no);
            $sheet->setCellValue('B' . $x, $u->user_name);
            $sheet->setCellValue('C' . $x, $u->user_address);

            $hobi = $this->db->query("SELECT * FROM hobi WHERE user_id = $u->user_id");
            $y = 3;
            foreach ($hobi->getResult() as $h) :
                $sheet->setCellValue('D' . $y, $h->hobi_name);
                $y++;
            endforeach;

            $x++;
            $no++;
        endforeach;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Hobi.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function html()
    {
        $data['user'] = $this->db->query("SELECT user.user_id, user.user_name, user.user_address FROM user");

        return view('html', $data);
    }
}
