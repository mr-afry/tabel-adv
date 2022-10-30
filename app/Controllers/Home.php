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
        // $data['user'] = $this->db->query("SELECT * FROM user ORDER BY user_id ASC")->getResultArray();
        // $data['hobi'] = $this->db->query("SELECT * FROM hobi ORDER BY user_id ASC")->getResultArray();
        // $data['tim'] = $this->db->query("SELECT * FROM tim ORDER BY user_id ASC")->getResultArray();
        // return view('welcome_message', $data);

    }

    public function excell()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'DAFTAR HOBI');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:E2')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama');
        $sheet->setCellValue('C2', 'Alamat');
        $sheet->setCellValue('D2', 'Hobi');
        $sheet->setCellValue('E2', 'Tim');

        $user = $this->db->query("SELECT user.user_id, user.user_name, user.user_address FROM user ORDER BY user.user_id ASC");
        $no = 1;
        foreach ($user->getResultArray() as $k => $u) :
            $row['no'] = $no;
            $row['name'] = $u['user_name'];
            $row['address'] = $u['user_address'];
            $row['user_hobi'] = $this->userhobi($u['user_id']);
            $row['user_tim'] = $this->usertim($u['user_id']);
            $row['key'] = $k;
            $row['indeks_row'] = (count($row['user_hobi']) > count($row['user_tim']) ? (count($row['user_hobi']) + $row['key']) : (count($row['user_tim']) + $row['key']));
            $list[] = $row;
            $no++;
        endforeach;

        echo "<pre>";
        print_r($list);
        echo "</pre>";

        echo "<table border='1'>";

        $x = 1;
        foreach ($list as $key => $frows) {
            $hl = $frows['user_hobi'];
            $tl = $frows['user_tim'];
            $countList = count($hl) > count($tl) ? count($hl) : count($tl);

            echo
            "<tr>";
            // ($key == 0 ? $x = $x : $x = ($x + $countList - 1));
            // ($key == 0 ? $x = $x : ($key == 1 ? $x = ($x + 1) : $x = ($x + $countList - 1)));

            // "<td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">A" . $x  . ($countList != 0 ? ":A" . ($x + $countList - 1) : '') . "-" . ($frows['no']) . "</td>
            echo "
            <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">0" . ($frows['no']) . "</td>
            <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">" . ($frows['name']) . "</td>
            <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">" . ($frows['address']) . "</td>
            <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">" . ($frows['indeks_row']) . "</td>
            <td>" . (count($hl) > 0 ? $hl[0]['hobi_name'] : '') . "</td>
            <td>" . (count($tl) > 0 ? $tl[0]['tim_name'] : '') . "</td>";
            echo
            "</tr>";

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
            $x++;
        }
        echo "</table>";
        // echo "<br><br>";

        // $htmlString = $this->_print_row1();

        // return $htmlString;
        die;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Hobi.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }




    public function excellhtml()
    {
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

        $htmlString = $this->_print_row1();

        // return $htmlString;
        // die;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);
        // Add Second Worksheet
        // $reader->setSheetIndex(1);
        // $secondHtmlString = $this->_print_row1($list);
        // $spreadsheet = $reader->loadFromString($secondHtmlString, $spreadsheet);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Hobi.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    // Without rowspan
    function _print_row($row_data = null)
    {
        if ($row_data == null) :
            $htmlString = '<style>
                            table {
                                boder: 1px;
                            }
                        </style>';
            $htmlString .= '<table id="hobi" border="1">
                            <thead>
                                <tr>
                                    <th colspan="5">DAFTAR HOBI 2</th>
                                </tr>
                                <tr>
                                    <th style="background-color:#FF0000; color:#FFFFFF; text-align:center;" border="1">No</th>
                                    <th style="background-color:#FF0000; color:#FFFFFF; text-align:center;">Nama</th>
                                    <th style="background-color:#FF0000; color:#FFFFFF; text-align:center;">Address</th>
                                    <th style="background-color:#FF0000; color:#FFFFFF; text-align:center;">Hobi</th>
                                    <th style="background-color:#FF0000; color:#FFFFFF; text-align:center;">Tim</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="3">1</td>
                                    <td rowspan="3">John1</td>
                                    <td rowspan="3">Australia</td>
                                    <td>Biker</td>
                                    <td>Jeni</td>
                                </tr>
                                <tr>
                                    <td>Camping</td>
                                    <td>Michael</td>
                                </tr>
                                <tr>
                                    <td>Jogging</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td rowspan="3">2</td>
                                    <td rowspan="3">Doe</td>
                                    <td rowspan="3">England</td>
                                    <td>Reading</td>
                                    <td>Alan</td>
                                </tr>
                                <tr>
                                    <td>Sports</td>
                                    <td>Mattew</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ian</td>
                                </tr>
                            </tbody>
                        </table>';
            return $htmlString;
        endif;


        $htmlString = '<table id="hobi" border="1">
                            <thead>
                                <tr>
                                    <th colspan="5">DAFTAR HOBI 2</th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Address</th>
                                    <th>Hobi</th>
                                    <th>Tim</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach ($row_data as $frows) {
            $hl = $frows['user_hobi'];
            $tl = $frows['user_tim'];
            // First Row
            $htmlString .=      "<tr>
                                    <td>" . ($frows['no']) . "</td>
                                    <td>" . ($frows['name']) . "</td>
                                    <td>" . ($frows['address']) . "</td>
                                    <td>" . (count($hl) > 0 ? $hl[0]['hobi_name'] : '') . "</td>
                                    <td>" . (count($tl) > 0 ? $tl[0]['tim_name'] : '') . "</td>
                                </tr>";

            // Second Row ...
            if (count($hl) > count($tl)) {
                for ($j = 1; $j < count($hl); $j++) {
                    $htmlString .=  "<tr>
                                        <td>" . "</td>
                                        <td>" . "</td>
                                        <td>" . "</td>
                                        <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                                        <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                                    </tr>";
                }
            } else {
                for ($j = 1; $j < count($tl); $j++) {
                    $htmlString .=  "<tr>
                                        <td>" . "</td>
                                        <td>" . "</td>
                                        <td>" . "</td>
                                        <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                                        <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                                    </tr>";
                }
            }
        }
        $htmlString .=      "</tbody>
                        </table>";
        return $htmlString;
    }

    // With rowspan
    function _print_row1($row_data = null)
    {
        if ($row_data == null) :
            $htmlString = '<table id="hobi" border="1">
                                <thead>
                                <tr>
                                    <th colspan="5">DAFTAR HOBI 2</th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Address</th>
                                    <th>Hobi</th>
                                    <th>Tim</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td rowspan="3">1</td>
                                    <td rowspan="3">Tomi</td>
                                    <td rowspan="3">Jakarta</td>
                                    <td>Masak</td>
                                    <td>Jeni</td>
                                </tr>
                                <tr>
                                    <td>Olahraga</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Joging</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td rowspan="3">2</td>
                                    <td rowspan="3">Roni</td>
                                    <td rowspan="3">Medan</td>
                                    <td>Mancing</td>
                                    <td>Michael</td>
                                </tr>
                                <tr>
                                    <td>Bersepeda</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Olahraga</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td rowspan="1">3</td>
                                    <td rowspan="1">Imam</td>
                                    <td rowspan="1">Aceh</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td rowspan="1">4</td>
                                    <td rowspan="1">Ridho</td>
                                    <td rowspan="1">Malang</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td rowspan="1">5</td>
                                    <td rowspan="1">Nurul</td>
                                    <td rowspan="1">Balikpapan</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>';
            return $htmlString;
        endif;


        $htmlString = '<table id="hobi" border="1">
                            <thead>
                                <tr>
                                    <th colspan="5">DAFTAR HOBI 2</th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Address</th>
                                    <th>Hobi</th>
                                    <th>Tim</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach ($row_data as $frows) {
            $hl = $frows['user_hobi'];
            $tl = $frows['user_tim'];
            $countList = count($hl) > count($tl) ? count($hl) : count($tl);
            // countlist = 0, rowspan="0" will tells the browser to span the cell to the last row of the table section 

            // phpspreadsheet cannot print rowspan without value, rowspan="" must be rowspan="1"
            // Solution 1

            // $htmlString .=
            // "<tr>
            //     <td rowspan='" . ($countList != 0 ? $countList : '1') . "'>" . ($frows['no']) . "</td>
            //     <td rowspan='" . ($countList != 0 ? $countList : '1') . "'>" . ($frows['name']) . "</td>
            //     <td rowspan='" . ($countList != 0 ? $countList : '1') . "'>" . ($frows['address']) . "</td>
            //     <td>" . (count($hl) > 0 ? $hl[0]['hobi_name'] : '') . "</td>
            //     <td>" . (count($tl) > 0 ? $tl[0]['tim_name'] : '') . "</td>
            // </tr>";

            // Solution 2
            $htmlString .=
                "<tr>
                    <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">" . ($frows['no']) . "</td>
                    <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">" . ($frows['name']) . "</td>
                    <td " . ($countList != 0 ? "rowspan='" . $countList . "'" : "") . ">" . ($frows['address']) . "</td>
                    <td>" . (count($hl) > 0 ? $hl[0]['hobi_name'] : '') . "</td>
                    <td>" . (count($tl) > 0 ? $tl[0]['tim_name'] : '') . "</td>
                </tr>";

            // Second Row ...
            if (count($hl) > count($tl)) {
                // Jml Hobi > Jml Tim
                for ($j = 1; $j < count($hl); $j++) {
                    $htmlString .=
                        "<tr>
                            <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                            <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                        </tr>";
                }
            } else {
                // Jml Hobi < Jml Tim
                for ($j = 1; $j < count($tl); $j++) {
                    $htmlString .=
                        "<tr>
                            <td>" . ($j < count($hl) ? $hl[$j]['hobi_name'] : '') . "</td>
                            <td>" . ($j < count($tl) ? $tl[$j]['tim_name'] : '') . "</td>
                        </tr>";
                }
            }
        }
        $htmlString .=      "</tbody>
                        </table>";
        return $htmlString;
    }

    public function excell1()
    {
        $htmlString = '<table id="hobi">
                            <thead>
                                <tr>
                                    <th colspan="5">DAFTAR HOBI 2</th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Address</th>
                                    <th>Hobi</th>
                                    <th>Tim</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="3">1</td>
                                    <td rowspan="3">John</td>
                                    <td rowspan="3">Australia</td>
                                    <td>Biker</td>
                                    <td>Jeni</td>
                                </tr>
                                <tr>
                                    <td>Camping</td>
                                    <td>Michael</td>
                                </tr>
                                <tr>
                                    <td>Jogging</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td rowspan="3">2</td>
                                    <td rowspan="3">Doe</td>
                                    <td rowspan="3">England</td>
                                    <td>Reading</td>
                                    <td>Alan</td>
                                </tr>
                                <tr>
                                    <td>Sports</td>
                                    <td>Mattew</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ian</td>
                                </tr>
                            </tbody>
                        </table>';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
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
