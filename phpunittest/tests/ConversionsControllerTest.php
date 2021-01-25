<?php 

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use PHPUnit\Framework\TestCase;
use App\Controllers\ConversionsController;

class ConversionsControllerTest extends TestCase {

    public function testConnversionResult(): void
    {

        $currency_source = 'GBP';
        $currency_target = 'USD';
        $amount = 12;

        $this->assertIsString($currency_source);
        $this->assertIsString($currency_target);
        $this->assertIsInt($amount);

        $this->assertEquals(strlen($currency_source), 3);
        $this->assertEquals(strlen($currency_target), 3);

        $this->assertIsfloat(
            ConversionsController::convert($currency_source, $currency_target, $amount)
        );  
    }
}
?> 