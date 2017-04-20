<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use NN\Util\IoC;
use NN\Lib\Http;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

    }
    public function index()
    {
        //IoC::get('SearchService')->getGoods();
        // $s = new AxjSmsService;
        // var_dump($s->send('13553606330', '1245667'));

        $source =  \Rx\Observable::just('http://api.jirengu.com/fm/getChannels.php');
        $stdoutObserver = new \Rx\Observer\CallbackObserver(
            function ($value) {
                echo $prefix . "Next value: " . $value . "\n";
                $response = Http::get($value);
                var_dump($response);
            },
            function ($error)  { echo $prefix . "Exception: " . $error->getMessage() . "\n"; },
            function ()        { echo $prefix . "Complete!\n"; }
        );
        $subscription = $source->subscribe($stdoutObserver);

    }
}
