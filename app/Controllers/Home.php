<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->parser = \Config\Services::parser();
    }

    public function index()
    {
        $data['user'] = $this->db->query("SELECT * FROM user ORDER BY user_id ASC")->getResultArray();
        $data['hobi'] = $this->db->query("SELECT * FROM hobi ORDER BY user_id ASC")->getResultArray();
        $data['tim'] = $this->db->query("SELECT * FROM tim ORDER BY user_id ASC")->getResultArray();
        return view('welcome_message', $data);
    }

    public function excell()
    {

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

        $user = $this->db->query("SELECT user.user_id, user.user_name, user.user_address FROM user ORDER BY user.user_id ASC");
        $no = 1;
        $x = 3;
        foreach ($user->getResultArray() as $u) :
            $user_id = $u['user_id'];
            $hobi = $this->db->query("SELECT * FROM hobi WHERE user_id = '$user_id' ORDER BY user_id ASC");
            // $count = count($hobi->getResultArray());

            $y = 0;
            foreach ($hobi->getResultArray() as $h) :
                // $sheet->setCellValue('A' . $x, $x . '-' . $no);
                // $sheet->setCellValue('B' . $x, $u['user_name']);
                // $sheet->setCellValue('C' . $x, $u['user_address']);
                // $sheet->setCellValue('D' . $x, $h['hobi_name']);
                echo '<table>';
                echo '<tr>';
                if ($y == 0) :
                    echo '<td rowspan="' . $y . '">' . $no . '</td>';
                    echo '<td rowspan="' . $y . '">' . $x . $u['user_name'] . '</td>';
                    echo '<td rowspan="' . $y . '">' . $x . $u['user_address'] . '</td>';
                    echo '<td rowspan="' . $y . '">' . $x . $h['hobi_name'] . '</td>';
                    echo '</tr>';
                    echo '</table>';
                endif;
            endforeach;

            $x++;
            $no++;
        endforeach;
        die;
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
        // $data = [
        //     'user' => $this->db->query("SELECT * FROM user")->getResultArray()
        // ];
        // return $this->parser->setData($data)->render('html');
    }

    public function htmlfinal()
    {
        $data['user'] = $this->db->query("SELECT user.user_id, user.user_name, user.user_address FROM user");
        return view('html-final', $data);
    }
}
