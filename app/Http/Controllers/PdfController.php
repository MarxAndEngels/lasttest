<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AutotekaRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfController extends Controller
{
  public function makePdfAutoteka(AutotekaRequest $request)
  {
    $dto = $request->toDto();
    $offer = $dto->getOfferForAutoteka();
    if(!$offer){
      abort(404);
    }
    $pdf = PDF::loadView('pdf.autoteka.autoteka', compact('offer'));
    return $pdf->download("Отчет автотеки {$offer['mark']['title']} {$offer['folder']['title']} {$offer['modification']['name']}, {$offer['year']}.pdf");
  }
}
