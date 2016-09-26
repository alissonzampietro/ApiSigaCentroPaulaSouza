<?php
namespace SigaApp\Faltas\Controller;

use Respect\Rest\Routable;
use SigaApp\Faltas\Model\FaltasModel;
use SigaApp\RequestSiga;


class FaltasController implements Routable {
	
	public function get() {
		//$faltas = new FaltasModel();
		//$faltas->get();
	    $siga = new RequestSiga();
	    $html = new simple_
	    $dados = $siga->returnData();
	    return $dados;
	}

}