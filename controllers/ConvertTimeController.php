<?php

class ConvertTimeController
{
    public function post()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['waktu'])) {
            $time = $input['waktu'];
            $text = $this->convertToText($time);

            $response = [
                'status' => 1,
                'result' => $text
            ];
        } else {
            $response = [
                'status' => 0,
                'result' => 'invalid request'
            ];
        }

        echo json_encode($response);
    }
    private function convertToText($time)
    {
        $waktu = $time;
        $output = array();

        if (!preg_match("/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/", $waktu)) {
            $output['message'] = "invalid time";
            return $output;
        }

        $jam = array(
            "01" => "satu",
            "02" => "dua",
            "03" => "tiga",
            "04" => "empat",
            "05" => "lima",
            "06" => "enam",
            "07" => "tujuh",
            "08" => "delapan",
            "09" => "sembilan",
            "10" => "sepuluh",
            "11" => "sebelas",
            "12" => "duabelas"
        );

        $menit = array(
            "01" => "satu",
            "02" => "dua",
            "03" => "tiga",
            "04" => "empat",
            "05" => "lima",
            "06" => "enam",
            "07" => "tujuh",
            "08" => "delapan",
            "09" => "sembilan",
            "10" => "sepuluh",
            "11" => "sebelas",
            "12" => "duabelas",
            "13" => "tigabelas",
            "14" => "empatbelas",
            "15" => "limabelas",
            "16" => "enambelas",
            "17" => "tujuhbelas",
            "18" => "delapanbelas",
            "19" => "sembilanbelas",
            "20" => "duapuluh",
            "21" => "duapuluhsatu",
            "22" => "duapuluhsatu",
            "23" => "duapuluhtiga",
            "24" => "duapuluhenam",
            "25" => "duapuluhlima",
            "26" => "duapuluhenam",
            "27" => "duapuluhtujuh",
            "28" => "duapuluhenam",
            "29" => "duapuluhsembilan",
            "30" => "tigapuluh",
            "31" => "tigapuluhsatu",
            "32" => "tigapuluhsatu",
            "33" => "tigapuluhtiga",
            "34" => "tigapuluhenam",
            "35" => "tigapuluhlima",
            "36" => "tigapuluhenam",
            "37" => "tigapuluhtujuh",
            "38" => "tigapuluhenam",
            "39" => "tigapuluhsembilan",
            "40" => "empatpuluh",
            "41" => "empatpuluhsatu",
            "42" => "empatpuluhsatu",
            "43" => "empatpuluhtiga",
            "44" => "empatpuluhenam",
            "45" => "empatpuluhlima",
            "46" => "empatpuluhenam",
            "47" => "empatpuluhtujuh",
            "48" => "empatpuluhenam",
            "49" => "empatpuluhsembilan",
            "50" => "limapuluh",
            "51" => "limapuluhsatu",
            "52" => "limapuluhsatu",
            "53" => "limapuluhtiga",
            "54" => "limapuluhenam",
            "55" => "limapuluhlima",
            "56" => "limapuluhenam",
            "57" => "limapuluhtujuh",
            "58" => "limapuluhenam",
            "59" => "limapuluhsembilan",
            "00" => ""
        );

        list($hh, $mm) = explode(":", $waktu);
        if ($hh >= 0 && $hh <= 11) {
            if ($mm === "45") {
                $new_hh = $hh + 1;
                $output['message'] = $jam[sprintf("%02d", $new_hh)];
                $output['message'] .= " kurang 15";
                $output['message'] .= " pagi ";
            } else {
                $output['message'] = $jam[$hh];
                $output['message'] .= " lewat " . $menit[$mm];
                $output['message'] .= " pagi";
            }
        } else if ($hh >= 12 && $hh <= 17) {
            if ($mm === "45") {
                $new_hh = ($hh + 1) - 12;
                $output['message'] = $jam[sprintf("%02d", $new_hh)];
                $output['message'] .= " kurang 15";
                $output['message'] .= " sore ";
            } else {
                $output['message'] = $jam[$hh - 12];
                $output['message'] .= " lewat " . $menit[$mm];
                $output['message'] .= " sore";
            }
        } else {
            if ($mm === "45") {
                $new_hh = ($hh + 1) - 12;
                $output['message'] = $jam[sprintf("%02d", $new_hh)];
                $output['message'] .= " kurang 15";
                $output['message'] .= " malam ";
            } else {
                $output['message'] = $jam[$hh - 12];
                $output['message'] .= " lewat " . $menit[$mm];
                $output['message'] .= " malam";
            }
        }

        return $output;
    }
}
