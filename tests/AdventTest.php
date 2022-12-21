<?php

use PHPUnit\Framework\TestCase;

final class AdventTest extends TestCase {
    private static $answers = [
        1 => [67016, 200116],
        2 => [13675, 14184],
        3 => [8018, 2518],
        4 => [573, 867],
        5 => ["RFFFWBPNS","CQQBBJFCS"],
        6 => [1760, 2974],
        7 => [1770595, 2195372]
    ];
    
    public function testAll() : void {
        for ($day = 1; $day <= count(self::$answers); $day++) {

            $output = popen(sprintf("php %'.02d/main.php", $day), "r");
            // this is a hacky solution which I don't like
            sleep(1);
            $contents = fread($output, 16000);
            $outputArray = explode("\n", $contents);

            // relies on everything outputting EOL at the end
            array_pop($outputArray);
            $this->assertEquals($outputArray, self::$answers[$day]);
            pclose($output);
        }
    }
}

