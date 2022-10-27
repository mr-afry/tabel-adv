<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends BaseController
{
    protected $db;
    protected $parser;

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
        foreach ($user->getResultArray() as $u) :
            $row['no'] = $no;
            $row['name'] = $u['user_name'];
            $row['address'] = $u['user_address'];
            $row['user_hobi'] = $this->userhobi($u['user_id']);
            $row['user_tim'] = $this->usertim($u['user_id']);
            $list[] = $row;
            $no++;
        endforeach;

        // echo "<pre>";
        // print_r($list);
        // echo "</pre>";

        echo "<table border='1'>";
        $x = 1;
        foreach ($list as $frows) {
            $hl = $frows['user_hobi'];
            $tl = $frows['user_tim'];
            echo
            "<tr>
            <td>" . $frows['no'] . "</td>
            <td>" . $frows['name'] . "</td>
            <td>" . $frows['address'] . "</td>
            <td>" . (count($hl) > 0 ? $hl[0]['hobi_name'] : '') . "</td>
            <td>" . (count($tl) > 0 ? $tl[0]['tim_name'] : '') . "</td>
            </tr>";

            // Second Row ...
            if (count($hl) > count($tl)) {
                // Jml Hobi > Jml Tim
                for ($j = 1; $j < count($hl); $j++) {
                    echo
                    "<tr>
                    <td>" . "</td>
                    <td>" . "</td>
                    <td>" . "</td>
                    <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                    <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                    </tr>";
                }
            } else {
                // Jml Hobi < Jml Tim
                for ($j = 1; $j < count($tl); $j++) {
                    echo
                    "<tr>
                    <td>" . "</td>
                    <td>" . "</td>
                    <td>" . "</td>
                    <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                    <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                    </tr>";
                }
            }
            $x++;
        }
        echo "</table>";
        echo "<br><br>";

        echo "<table border='1'>";
        foreach ($list as $frows) {
            $hl = $frows['user_hobi'];
            $tl = $frows['user_tim'];
            $countList = count($hl) > count($tl) ? count($hl) : count($tl);
            // countlist = 0, rowspan="0" will tells the browser to span the cell to the last row of the table section 
            echo
            "<tr>
                <td rowspan='" . ($countList != 0 ? $countList : '') . "'>" . ($frows['no']) . "</td>
                <td rowspan='" . ($countList != 0 ? $countList : '') . "'>" . ($frows['name']) . "</td>
                <td rowspan='" . ($countList != 0 ? $countList : '') . "'>" . ($frows['address']) . "</td>
                <td>" . (count($hl) > 0 ? $hl[0]['hobi_name'] : '') . "</td>
                <td>" . (count($tl) > 0 ? $tl[0]['tim_name'] : '') . "</td>
            </tr>";

            // Second Row ...
            if (count($hl) > count($tl)) {
                // Jml Hobi > Jml Tim
                for ($j = 1; $j < count($hl); $j++) {
                    echo
                    "<tr>
                        <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                        <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                    </tr>";
                }
            } else {
                // Jml Hobi < Jml Tim
                for ($j = 1; $j < count($tl); $j++) {
                    echo
                    "<tr>
                        <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                        <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                    </tr>";
                }
            }
        }
        echo "</table>";
        die;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Hobi.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function userhobi($uid)
    {
        return $this->db->query("SELECT * FROM hobi WHERE user_id = '$uid' ORDER BY user_id ASC")->getResultArray();
    }

    public function usertim($uid)
    {
        return $this->db->query("SELECT * FROM tim WHERE user_id = '$uid' ORDER BY user_id ASC")->getResultArray();
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
