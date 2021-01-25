<?php


namespace App\Controllers;

use App\Core\App;
class ConversionsController
{

    protected $symbols;

    public function __construct()
    {
        $this->symbols = symbols();
    }

    /*
     * get latest records from conversions table (5 rows you can specify a number by default is 10)
     */
    public function index()
    {
        $conversions = App::get('database')->latest('conversions', 5);

        return view('index', compact('conversions'));
    }

    public function create()
    {
        return view('create', [
            'symbols' => $this->symbols
        ]);
    }

    public function store()
    {
        $source_currency = $_POST['source_currency'];
        $target_currency = $_POST['target_currency'];
        $amount = $_POST['amount'];

        $errors = $this->validator($source_currency, $target_currency, $amount);
        if (count($errors) > 0) {
            return view('create', [
                'errors' => $errors,
                'source_currency' => $source_currency,
                'target_currency' => $target_currency,
                'amount' => $amount,
                'symbols' => $this->symbols,
            ]);
        } else {
            $result = $this->convert($source_currency, $target_currency, $amount);
            if ($result > 0) {
                App::get('database')->insert('conversions', [
                    'source' => $source_currency,
                    'target' => $target_currency,
                    'amount' => $amount,
                    'result' => $result,
                ]);

                //$r = $result['result'] ?? $amount * 0.9;
                return view('create', [
                    'success' => "Data added successfully <br>
                            <strong> {$amount}  {$source_currency}  =  {$result} {$target_currency} </strong>",
                    'symbols' => $this->symbols
                ]);
            } else {
                return view('create', [
                    'errors' => ['Access Restricted, please check docs'],
                    'symbols' => $this->symbols,
                ]);
            }
        }

    }

    private function validator($source_currency, $target_currency, $amount)
    {
        $formErrors = array();
        if (empty($source_currency)) {
            $formErrors[] = "Source currency <strong>required</strong>";
        } elseif (!array_key_exists($source_currency, $this->symbols)) {
            $formErrors[] = "Source currency <strong>invalid</strong>";
        }

        if (empty($target_currency)) {
            $formErrors[] = "Target currency <strong>required</strong>";
        } elseif (!array_key_exists($target_currency, $this->symbols)) {
            $formErrors[] = "Target currency <strong>invalid</strong>";
        }

        if (empty($amount)) {
            $formErrors[] = "Amount <strong>required</strong>";
        } elseif (!is_numeric($amount)) {
            $formErrors[] = "Amount must be <strong>numeric</strong>";
        }

        return $formErrors;
    }


    public function convert($source_currency, $target_currency, $amount)
    {
        $currency_base = 'EUR';
        $ch = curl_init('http://data.fixer.io/api/latest?access_key=' . App::get('config')['services']['fixer_key'] . '&base=' . $currency_base . '&symbols=' . $source_currency .','. $target_currency .'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);
        $valueSourceToEuro = $amount / $exchangeRates['rates'][$source_currency];
        $valueSourceToTarget = $valueSourceToEuro * $exchangeRates['rates'][$target_currency];
        return $valueSourceToTarget;

    }
}