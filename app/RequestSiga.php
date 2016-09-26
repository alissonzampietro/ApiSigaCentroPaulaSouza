<?php
namespace SigaApp;

use Symfony\Component\Yaml\Yaml;

class RequestSiga
{
    private static $dados;
    
    public function __construct()
    {
        self::$dados = Yaml::parse(file_get_contents(BASEPATH."/routessiga.yml"));
    }

    public function returnData()
    {
        $username="121680048";
        $password="xxxxxxxx";
        $login="https://www.sigacentropaulasouza.com.br/aluno/login.aspx?4581370c99f8072a28f3aec677fdbfbe,,gx-no-cache=1458272143978s";
        $urlFaltas = "https://www.sigacentropaulasouza.com.br/aluno/faltasparciais.aspx";
        
        
        $postdata = 'vSIS_USUARIOID='.$username.'&vSIS_USUARIOSENHA='.$password.'&BTCONFIRMA=Confirmar&GXState={"_EventName":"EENTER.","_EventGridId":"","_EventRowId":"","MPW0005_CMPPGM":"login_top.aspx","MPW0005GX_FocusControl":"","vREC_SIS_USUARIOID":"","GX_FocusControl":"vSIS_USUARIOID","GX_AJAX_KEY":"670337884953F2AD3069AEA655EFB534","AJAX_SECURITY_TOKEN":"B8EB9B12BEB5BDAA61133D16DC8F3FE1F2675B852B42470B5D381AF40ACBB31A","GX_CMP_OBJS":{"MPW0005":"login_top"},"sCallerURL":"","GX_RES_PROVIDER":"GXResourceProvider.aspx","GX_THEME":"GeneXusX","_MODE":"","Mode":"","IsModified":"1"}';
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $login);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_REFERER, $login);
        
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec ($ch);
        
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        
        $sessionId = $cookies['ASP_NET_SessionId'];
        
        curl_setopt ($ch, CURLOPT_URL, $urlFaltas);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_REFERER, $urlFaltas);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: ASP.NET_SessionId=".$sessionId));
        $result = curl_exec ($ch);
        curl_close($sh);
        return $result;
    }
}