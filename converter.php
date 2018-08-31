<?php

class Converter {

    public function formatAsNumber($text) {
        $parts = explode('.', $text);
        if (!count($parts)) {
            return "0.00";
        }

        $mainNumber = array_reverse(str_split($parts[0]));
        $newNumber = "";
        $count = 0;
        foreach ($mainNumber as $number) {
            $number = (int)$number;
            if (!is_numeric($number)) {
                continue;
            }
            if ($count == 3) {
                $count = 0;
                $newNumber = "," . $newNumber;
            }
            $newNumber = $number . $newNumber;
            $count++;
        }

        $decimal = "";
        $count = 0;
        if (count($parts) > 1) {
            $decimalNumber = str_split($parts[1]);
            foreach ($decimalNumber as $number) {
                $number = (int)$number;
                if (!is_numeric($number)) {
                    continue;
                }
                $decimal .= $number;
                $count++;
                if ($count == 2) {
                    break;
                }
            }
        }

        while (strlen($decimal) < 2) {
            $decimal = '0' . $decimal;
        }

        return $newNumber . "." . $decimal;
    }

    public static function convert($text) {
        $units = array(
            1 => "one", 2 => "two", 3 => "three", 4 => "four", 5 => "five",
            6 => "six", 7 => "seven", 8 => "eight", 9 => "nine", 10 => "ten",
            11 => "eleven", 12 => "twelve", 13 => "thirteen", 14 => "fourteen",
            15 => "fifteen", 16 => "sixteen", 17 => "seventeen", 18 => "eighteen",
            19 => "nineteen"
        );
        $tens = array(
            2 => "twenty", 3 => "thirty", 4 => "forty", 5 => "fifty",
            6 => "sixty", 7 => "seventy", 8 => "eighty", 9 => "ninety"
        );
        $hundreds = array(
            "hundred", "thousand", "million", "billion", "trillion", "quadrillion",
            "quadrillion", 'Quintillion', 'Sextillion', 'Septillion', 'Octillion',
            'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion',
            'Quattuordecillion', 'Quindecillion', 'Sexdecillion', 'Sexdecillion',
            'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion',
            'Centillion'
        );
        $formattedNumber = self::formatAsNumber($text);

        if((int)$formattedNumber == 0) {
            return "zero";
        }
        $formattedNumberArray = explode(".", $formattedNumber);//separate the whole number from decimal
        $wholePart = $formattedNumberArray[0];
        $decimalPart = $formattedNumberArray[1];
        $wholeArray = array_reverse(explode(",", $wholePart));
        krsort($wholeArray);
        $numberInWordArray = [];
        foreach ($wholeArray as $index => $group) {
            $group = (int)$group;
            $numberInWord = "";

            $hundredPart = ($group / 100);

            if ($hundredPart >= 1) {
                $numberInWord .= $units[$hundredPart] . ' hundred';
            }
            $remainder = $group % 100;

            if ($remainder > 0 && $remainder < 20) {
                if ($hundredPart >= 1) {
                    $numberInWord .= " and ";
                }
                $numberInWord .= $units[$remainder];
            } else {
                $tenPart = ($remainder / 10);
                if ($tenPart) {
                    if ($hundredPart >= 1) {
                        $numberInWord .= " and ";
                    }

                    $numberInWord .= $tens[$tenPart];
                }

                $unitPart = $group % 10;
                if ($unitPart) {
                    if ($tenPart) {
                        $numberInWord .= "-";
                    } else if ($hundredPart) {
                        $numberInWord .= " and ";
                    }
                    $numberInWord .= $units[$unitPart];
                }
            }


            if(trim($numberInWord)) {
                if ($index > 0) {
                    $numberInWord .= " " . $hundreds[$index];
                }

                $numberInWordArray[] = $numberInWord;
            }
        }
        return strtolower(implode(', ', $numberInWordArray));
    }
}

$text = "";
$number = "";
if (isset($_POST['number'])) {
    $number = $_POST['number'];
    $text = Converter::convert($number);
}
?>


<form method="post">
    <input name="number" value="<?php echo $number; ?>" placeholder="Enter number to convert" style="width: 250px;"><br>
    <?php if ($text): ?>
        <h4><?php echo $text ?></h4>
    <?php endif ?>
    <button>Convert</button>
</form>