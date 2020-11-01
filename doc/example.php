<?php

//скрыть, если не пользуетесь прокси.
$proxy="127.0.0.1:1337";

$ch = curl_init();

curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

curl_setopt ($ch, CURLOPT_PROXYTYPE, 7);

curl_setopt_array($ch, array(
    CURLOPT_URL => "https://openapi.nalog.ru:8090/open-api/AuthService/0.1?wsdl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"
<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiMessageConsumerService/types/1.0\">
<soapenv:Header/>
<soapenv:Body>
<ns:GetMessageRequest>
<ns:Message>\n<tns:AuthRequest xmlns:tns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0\">
<tns:AuthAppInfo>
<tns:MasterToken>/*YOUR MASTER TOKEN HERE*/</tns:MasterToken>
</tns:AuthAppInfo>
</tns:AuthRequest>
</ns:Message>
</ns:GetMessageRequest>
</soapenv:Body>
</soapenv:Envelope>",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: text/xml"
    ),
));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
//echo $response;
echo $error;
//echo html_entity_decode($response);

$dom = new DOMDocument();
$dom->loadXML($response);
foreach($dom->getElementsByTagName('Token') as $element){
    $tempToken = $element->nodeValue;
}

$temptoken = $tempToken;
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

curl_setopt ($ch, CURLOPT_PROXYTYPE, 7);

curl_setopt_array($ch, array(
    CURLOPT_URL => "https://openapi.nalog.ru:8090/open-api/ais3/KktService/0.1?wsdl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"
                 xmlns:ns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0\">
    <soapenv:Header/>
    <soapenv:Body>
        <ns:SendMessageRequest>
            <ns:Message>
                <tns:GetTicketRequest xmlns:tns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0\">
                    <tns:CheckTicketInfo>
                        <tns:Sum>12500</tns:Sum>
                        <tns:Date>2020-04-23T12:08:00</tns:Date>
                        <tns:Fn>9287440300077658</tns:Fn>
                        <tns:TypeOperation>1</tns:TypeOperation>
                        <tns:FiscalDocumentId>166865</tns:FiscalDocumentId>
                        <tns:FiscalSign>4264393268</tns:FiscalSign>
                    </tns:CheckTicketInfo>
                </tns:GetTicketRequest>
            </ns:Message>
        </ns:SendMessageRequest>
    </soapenv:Body>
</soapenv:Envelope>",
    CURLOPT_HTTPHEADER => array(
        "FNS-OpenApi-Token: ". $temptoken ."",
        "FNS-OpenApi-UserToken: /*YOUR MASTER TOKEN HERE*/",
        "Content-Type: text/xml"
    ),
));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
//echo $response;
echo $error;
//echo html_entity_decode($response);

$dom = new DOMDocument();
$dom->loadXML($response);
foreach($dom->getElementsByTagName('MessageId') as $element ){
    $messageId = $element->nodeValue;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

curl_setopt ($ch, CURLOPT_PROXYTYPE, 7);

curl_setopt_array($ch, array(
    CURLOPT_URL => "https://openapi.nalog.ru:8090/open-api/ais3/KktService/0.1?wsdl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0\">
   <soapenv:Header/>
   <soapenv:Body>
      <ns:GetMessageRequest>
         <ns:MessageId>".$messageId."</ns:MessageId>
      </ns:GetMessageRequest>
   </soapenv:Body>
</soapenv:Envelope>",
    CURLOPT_HTTPHEADER => array(
        "FNS-OpenApi-Token: ". $temptoken ."",
        "FNS-OpenApi-UserToken: /*YOUR MASTER TOKEN HERE*/",
        "Content-Type: text/xml"
    ),
));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
//echo $response;
echo $error;
//echo html_entity_decode($response);

$dom = new DOMDocument();
$dom->loadXML($response);

foreach($dom->getElementsByTagName('Code') as $element ){
    $code = $element->nodeValue;
}
foreach($dom->getElementsByTagName('Ticket') as $element ){
    $message = $element->nodeValue;
}
$x = json_decode($message, true);
var_dump($x);
