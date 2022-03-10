<?php
namespace App\Services\Transaction;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use PhpParser\Node\Scalar\MagicConst\Dir;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode($ref,$username,$adresse,$date)
    {
        $url = 'http://127.0.0.1:8000/transaction/afficheTransaction/';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');



        $path = dirname(__DIR__, 3).'/public/';

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data('Facture payable par :                                                                                                              '.$username.'                                                      '.$adresse.'                                                             Le '.$date.'                                                              Réference :                                                        00'.$ref.'00')
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(350)
            ->margin(10)
            //->logoPath($path.'img/logoV1.png')
            ->labelText($dateString)
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile($path.'qr-code/'.$namePng);

        return $result->getDataUri();


    }
}
