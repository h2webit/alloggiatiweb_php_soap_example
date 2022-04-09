<?php

$wsdl = "https://alloggiatiweb.poliziadistato.it/service/service.asmx?wsdl";

// Accessi forniti da Alloggiatiweb
$username = "XXXXX";
$password = "XXXX";

$wskey = "XXXXX"; // Si ottiene facendo login su Alloggiatiweb, c'è una funzione speficia per generare la chiave.

// Abilitare per ottenere i CSV delle tabelle di supporto di Alloggiati come gli Stati, Tipi documento etc.
$mostra_tabelle = false;

echo "<pre>";

try {
    $client = new SoapClient($wsdl);

    // Debug funzioni
    echo "--------- Debug di tutte le funzioni disponibili\r\n";
    print_r($client->__getFunctions());

    // Generate token
    $token_request = $client->GenerateToken(array("Utente" => $username, "Password" => $password, "WsKey" => $wskey));
    $token = $token_request->GenerateTokenResult->token;
    echo "--------- Token ottenuto: \r\n";
    echo $token;
    echo "\r\n";
    echo "\r\n";
    echo "\r\n";

    if ($mostra_tabelle) {
        // Ottenere tabelle (luoghi, tipo alloggiato, stati, documento)
        echo "Tabelle: Tipi_Documento\r\n";
        $tabella = $client->Tabella(array("Utente" => $username, "token" => $token, "tipo" => "Tipi_Documento", "CSV" => 1));
        print_r($tabella->CSV);

        echo "\r\n";

        echo "Tabelle: Tipi_Alloggiato\r\n";
        $tabella = $client->Tabella(array("Utente" => $username, "token" => $token, "tipo" => "Tipi_Alloggiato", "CSV" => 1));
        print_r($tabella->CSV);

        echo "\r\n";

        echo "Tabelle: ListaAppartamenti\r\n";
        $tabella = $client->Tabella(array("Utente" => $username, "token" => $token, "tipo" => "ListaAppartamenti", "CSV" => 1));
        print_r($tabella->CSV);

        echo "\r\n";

        echo "Tabelle: Luoghi\r\n";
        $tabella = $client->Tabella(array("Utente" => $username, "token" => $token, "tipo" => "Luoghi", "CSV" => 1));
        print_r($tabella->CSV);

        echo "\r\n";
    }


    echo "--------- Inserimento schedine su un appartamento specifico\r\n";

    // Test invio schedine di un appartamento specifico
    $appartamento_id = 0;
    $schedine[] = "1708/04/20225 ROSSETTI                                          MARIO                         101/08/1981           100000215100000215IDENT          AT11143366100000100";
    $schedine[] = "1908/04/20225 ROSSETTI                                          GIOVANNI                      201/08/1981           100000215100000215IDENT           AT3334478100000100";

    $invio = $client->GestioneAppartamenti_Test(array("Utente" => $username, "token" => $token, "IdAppartamento" => $appartamento_id, "ElencoSchedine" => $schedine));
    print_r($invio);
} catch (SoapFault $exception) {
    echo "Error Code: {$exception->getMessage()}";
}
