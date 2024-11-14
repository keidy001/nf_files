
<?php

class NumberToLetter {

    public function unite($nombre) {
        $unite = '';
        switch($nombre) {
            case 0: $unite = "zéro"; break;
            case 1: $unite = "un"; break;
            case 2: $unite = "deux"; break;
            case 3: $unite = "trois"; break;
            case 4: $unite = "quatre"; break;
            case 5: $unite = "cinq"; break;
            case 6: $unite = "six"; break;
            case 7: $unite = "sept"; break;
            case 8: $unite = "huit"; break;
            case 9: $unite = "neuf"; break;
        }
        return $unite;
    }

    public function dizaine($nombre) {
        $dizaine = '';
        switch($nombre) {
            case 10: $dizaine = "dix"; break;
            case 11: $dizaine = "onze"; break;
            case 12: $dizaine = "douze"; break;
            case 13: $dizaine = "treize"; break;
            case 14: $dizaine = "quatorze"; break;
            case 15: $dizaine = "quinze"; break;
            case 16: $dizaine = "seize"; break;
            case 17: $dizaine = "dix-sept"; break;
            case 18: $dizaine = "dix-huit"; break;
            case 19: $dizaine = "dix-neuf"; break;
            case 20: $dizaine = "vingt"; break;
            case 30: $dizaine = "trente"; break;
            case 40: $dizaine = "quarante"; break;
            case 50: $dizaine = "cinquante"; break;
            case 60: $dizaine = "soixante"; break;
            case 70: $dizaine = "soixante-dix"; break;
            case 80: $dizaine = "quatre-vingt"; break;
            case 90: $dizaine = "quatre-vingt-dix"; break;
        }
        return $dizaine;
    }

    public function numberToLetter($nombre) {
        if(strlen(str_replace(" ", "", $nombre)) > 15) return "dépassement de capacité";
        if(!is_numeric(str_replace(" ", "", $nombre))) return "Nombre non valide";

        $nb = (int)str_replace(" ", "", $nombre);
        if(ceil($nb) != $nb) return "Nombre avec virgule non géré.";

        $n = strlen($nb);
        $numberToLetter = "";

        switch($n) {
            case 1:
                $numberToLetter = $this->unite($nb);
                break;
            case 2:
                if($nb > 19) {
                    $quotient = intdiv($nb, 10);
                    $reste = $nb % 10;
                    if($nb < 71 || ($nb > 79 && $nb < 91)) {
                        $numberToLetter = $this->dizaine($quotient * 10);
                        if($reste == 1) $numberToLetter .= "-et-" . $this->unite($reste);
                        elseif($reste > 1) $numberToLetter .= "-" . $this->unite($reste);
                    } else {
                        $numberToLetter = $this->dizaine(($quotient - 1) * 10) . "-" . $this->dizaine(10 + $reste);
                    }
                } else {
                    $numberToLetter = $this->dizaine($nb);
                }
                break;
            case 3:
                $quotient = intdiv($nb, 100);
                $reste = $nb % 100;
                if($quotient == 1 && $reste == 0) $numberToLetter = "cent";
                elseif($quotient == 1 && $reste != 0) $numberToLetter = "cent " . $this->numberToLetter($reste);
                elseif($quotient > 1 && $reste == 0) $numberToLetter = $this->unite($quotient) . " cents";
                else $numberToLetter = $this->unite($quotient) . " cent " . $this->numberToLetter($reste);
                break;
            case 4:
            case 5:
            case 6:
                $quotient = intdiv($nb, 1000);
                $reste = $nb - $quotient * 1000;
                if($quotient == 1 && $reste == 0) $numberToLetter = "mille";
                elseif($quotient == 1 && $reste != 0) $numberToLetter = "mille " . $this->numberToLetter($reste);
                elseif($quotient > 1 && $reste == 0) $numberToLetter = $this->numberToLetter($quotient) . " mille";
                else $numberToLetter = $this->numberToLetter($quotient) . " mille " . $this->numberToLetter($reste);
                break;
            case 7:
            case 8:
            case 9:
                $quotient = intdiv($nb, 1000000);
                $reste = $nb % 1000000;
                if($quotient == 1 && $reste == 0) $numberToLetter = "un million";
                elseif($quotient == 1 && $reste != 0) $numberToLetter = "un million " . $this->numberToLetter($reste);
                elseif($quotient > 1 && $reste == 0) $numberToLetter = $this->numberToLetter($quotient) . " millions";
                else $numberToLetter = $this->numberToLetter($quotient) . " millions " . $this->numberToLetter($reste);
                break;
            case 10:
            case 11:
            case 12:
                $quotient = intdiv($nb, 1000000000);
                $reste = $nb - $quotient * 1000000000;
                if($quotient == 1 && $reste == 0) $numberToLetter = "un milliard";
                elseif($quotient == 1 && $reste != 0) $numberToLetter = "un milliard " . $this->numberToLetter($reste);
                elseif($quotient > 1 && $reste == 0) $numberToLetter = $this->numberToLetter($quotient) . " milliards";
                else $numberToLetter = $this->numberToLetter($quotient) . " milliards " . $this->numberToLetter($reste);
                break;
            case 13:
            case 14:
            case 15:
                $quotient = intdiv($nb, 1000000000000);
                $reste = $nb - $quotient * 1000000000000;
                if($quotient == 1 && $reste == 0) $numberToLetter = "un billion";
                elseif($quotient == 1 && $reste != 0) $numberToLetter = "un billion " . $this->numberToLetter($reste);
                elseif($quotient > 1 && $reste == 0) $numberToLetter = $this->numberToLetter($quotient) . " billions";
                else $numberToLetter = $this->numberToLetter($quotient) . " billions " . $this->numberToLetter($reste);
                break;
        }

        if(substr($numberToLetter, -strlen("quatre-vingt")) === "quatre-vingt") {
            $numberToLetter .= "s";
        }

        return $numberToLetter;
    }
    public function convert($nombre) {
        return $this->NumberToLetter($nombre);
    }
}


?>
