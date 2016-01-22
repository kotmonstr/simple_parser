<?php
function vd($arr)
{
    var_dump($arr);
    die('Stop By Kostya');
}

;
//Connect to DB
function Connect()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "simple_parser";
    $mysqli = new mysqli($host, $user, $password, $db);

    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    return $mysqli;
}

//Add Url to arr
function AddURL($response, $info, $request)
{
    $ArrLINKS = [];
    if (preg_match('/onclick/', $response, $out)) {
        preg_match_all('/<a[^>]+onclick=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/',
            $response, $out);
        $ArrResult = $out[2];
        foreach ($ArrResult as $key => $val) {
            if (strlen(substr($val, 19, -2)) > 17) {
                $ArrLINKS[$key]['URL'] = substr($val, 19, -2);
                $ArrLINKS[$key]['Status'] = $info['http_code'];
                $ArrLINKS[$key]['DtAdded'] = time();
                $ArrLINKS[$key]['Source'] = SOURCE;
                ?>
                <!-- <a href="<?= substr($val, 19, -2); ?>">Ссылка</a></br>' -->
            <?php }
        }
    }
    //var_dump($ArrLINKS);
    return $ArrLINKS;
}

//Добавление Url to table
function AddUrlToTable($ArrLINKS)
{
    echo '<hr><br />';
    $mysqli = Connect();
    $IteratorNew = 0;
    $IteratorUpdate = 0;
    foreach ($ArrLINKS as $key => $row) {
        $url = isset($row['URL']) ? $row['URL'] : 'ERROR';
        $status = isset($row['Status']) ? $row['Status'] : 0;
        $DtAdded = isset($row['DtAdded']) ? $row['DtAdded'] : 0;
        $source = isset($row['Source']) ? $row['Source'] : 'ERROR';

        // Поиск дубликата
        $query = "SELECT * FROM `url` WHERE `url` = '" . $url . "'";
        $result = $mysqli->query($query);
        $count = $result->num_rows;
        if ($count > 0) {
            $IteratorUpdate++;
            echo 'it is dublicate<br />';
            $query = "UPDATE `url` SET `ID`=NULL,`url`='" . $url . "',`DtAdded`='" . $DtAdded . "',`DtProcessed`=0,`DtUpdated`='" . time() . "',`Status`=" . $status . ",`SourceID`=0,`House_ID`=0,`Source`='" . $source . "' WHERE 'url'='" . $url . "'";
            $result = $mysqli->query($query);
            if ($result == 'true') {
                echo 'ok-updated<br />';
            } else {
                echo 'error!!!<br />';
            }
        } else {
            $IteratorNew++;
            echo 'it is a new record<br />';
            $query = "INSERT INTO `url`(`ID`, `url`, `DtAdded`, `DtProcessed`, `DtUpdated`, `Status`, `SourceID`, `House_ID`, `Source`) VALUES (NULL,'" . $url . "','" . $DtAdded . "',0,0," . $status . ",0,0,'" . $source . "')";
            $result = $mysqli->query($query);
            if ($result == 'true') {
                echo 'ok- added<br />';
            } else {
                echo 'error!!!<br />';
            }
        }
    }
    echo 'Было добавленно: ' . $IteratorNew . ' записи(eй).<br />';
    echo 'Обновленно: ' . $IteratorUpdate . ' записи(eй).<br />';
}

//Получаю список Необработанных ссылок LIMIT1 - пока 1ссылку
function getListNotParsingYet()
{
    $mysqli = Connect();
    $limit = GLOBAL_LIMIT_PER_ACTION ;
    $query = "SELECT * FROM `url` WHERE `DtProcessed` = 0 LIMIT ".$limit;
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
         $i = 0;
        $arrListUrl = [];
        while ($row = $result->fetch_assoc()) {
            $i++;
            $arrListUrl[$row['ID']] = $row['url'];
            $url= $row['url'];
            //Todo Пометить как Обработанные
            $query2 = "UPDATE `url` SET `DtProcessed` =1 WHERE `url`='" . $url . "'";
            $mysqli->query($query2);
        }
        //var_dump($arrListUrl);
    } else {
        echo "Все ссылки на дома распарсены! Попробуйте обновить ссылки";
        return false;
    }
    return $arrListUrl;

}

//Парсю урли для домов
function ParsingURL($response, $info, $request)
{
    $arrResult = [];
    $arrTemp = [];

        $html = str_get_html($response); // загружаем данные

        $e = $html->find("#ctl00_lblCaseNumber", 0);
        $arrResult['СaseNumber'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblAddress", 0);
        $arrResult['Address'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblBedBath", 0);
        $arrResult['Bed/Bath'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblTotalRooms", 0);
        $arrResult['Total Rooms'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblSqft", 0);
        $arrResult['Square Feet'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblYear", 0);
        $arrResult['Year'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblHousingType", 0);
        $arrResult['Housing Type'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblStories", 0);
        $arrResult['Number of Stories'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblHOAFees", 0);
        $arrResult['HOA Fees'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblRevitArea", 0);
        $arrResult['Revitalization Area'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblParking", 0);
        $arrResult['Parking'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblOutdoorAmenities", 0);
        $arrResult['Patio/Deck'] = isset($e->plaintext) ? $e->plaintext : NULL;


        ////////////////////////// listing /////////////////////////////

        $e = $html->find("#ctl00_lblListdate", 0);
        $arrTemp['List Date'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblBidPeriod", 0);
        $arrTemp['Listing Period'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblBidDeadline", 0);
        $arrTemp['Period Deadline'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblPrice", 0);
        $arrTemp['List Price'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblFHA", 0);
        $arrTemp['FHA Financing'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblEligible", 0);
        $arrTemp['203K Eligible'] = isset($e->plaintext) ? $e->plaintext : NULL;

        $arrResult['LISTING'] = serialize(array('List Date' => $arrTemp['List Date'],
            'Listing Period' => $arrTemp['Listing Period'],
            'Period Deadline' => $arrTemp['Period Deadline'],
            'List Price' => $arrTemp['List Price'],
            'FHA Financing' => $arrTemp['FHA Financing'],
            '203K Eligible' => $arrTemp['203K Eligible']
        ));

        //////////////////////// Agent Info ////////////////////////////////

        $e = $html->find("#ctl00_lblAssetManagerCompanyName", 0);
        $arrTemp['Company Name'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblAssetManagerContactName", 0);
        $arrTemp['Contact Name'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblAssetManagerAddress", 0);
        $arrTemp['Address'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblAssetManagerPhoneNumber", 0);
        $arrTemp['Phone Number'] =isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblAssetManagerFaxNumber", 0);
        $arrTemp['Fax Number'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lnkAssetManageremail", 0);
        $arrTemp['Email'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lnkAssetManagerWebsite", 0);
        $arrTemp['Website'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_txtAMNotes", 0);
        $arrTemp['Additional Comments'] = isset($e->plaintext) ? $e->plaintext : NULL;

        $arrResult['AGENT'] = serialize(array('Company Name' => $arrTemp['Company Name'],
            'Contact Name' => $arrTemp['Contact Name'],
            'Address' => $arrTemp['Address'],
            'Phone Number' => $arrTemp['Phone Number'],
            'Fax Number' => $arrTemp['Fax Number'],
            'Email' => $arrTemp['Email'],
            'Website' => $arrTemp['Website'],
            'Additional Comments' => $arrTemp['Additional Comments']
        ));

        ////////////////////////// Field Service Manager ////////////////////////////////

        $e = $html->find("#ctl00_lblservicemanagerCompanyName", 0);
        $arrTemp['Company Name'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblservicemanagerContactName", 0);
        $arrTemp['Contact Name'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblservicemanagerAddress", 0);
        $arrTemp['Address'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblservicemanagerPhoneNumber", 0);
        $arrTemp['Phone Number'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblservicemanagerFaxNumber", 0);
        $arrTemp['Fax Number'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lnkservicemanagerEmail", 0);
        $arrTemp['Email'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lnkservicemanagerWebsite", 0);
        $arrTemp['Website'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_txtFSMNotes", 0);
        $arrTemp['Additional Comments'] = isset($e->plaintext) ? $e->plaintext : NULL;

        $arrResult['MANAGER'] = serialize(array('Company Name' => $arrTemp['Company Name'],
            'Contact Name' => $arrTemp['Contact Name'],
            'Address' => $arrTemp['Address'],
            'Phone Number' => $arrTemp['Phone Number'],
            'Fax Number' => $arrTemp['Fax Number'],
            'Email' => $arrTemp['Email'],
            'Website' => $arrTemp['Website'],
            'Additional Comments' => $arrTemp['Additional Comments']
        ));

        ////////////////////////////////////   Broker /////////////////////////////

        $e = $html->find("#ctl00_lblListingBrokerCompanyName", 0);
        $arrTemp['Company Name'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblListingBrokerContactName", 0);
        $arrTemp['Contact Name'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblListingBrokerAddress", 0);
        $arrTemp['Address'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblListingBrokerPhoneNumber", 0);
        $arrTemp['Phone Number'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lblListingBrokerFaxNumber", 0);
        $arrTemp['Fax Number'] = isset($e->plaintext) ? $e->plaintext : NULL;
        $e = $html->find("#ctl00_lnkListingBrokeremail", 0);
        $arrTemp['Email'] = isset($e->plaintext) ? $e->plaintext : NULL;

        $arrResult['BROKER'] = serialize(array('Company Name' => $arrTemp['Company Name'],
            'Contact Name' => $arrTemp['Contact Name'],
            'Address' => $arrTemp['Address'],
            'Phone Number' => $arrTemp['Phone Number'],
            'Fax Number' => $arrTemp['Fax Number'],
            'Email' => $arrTemp['Email']
        ));
////////////////////////////////// images //////////////////////////////////////////

        $i = 0;$item = [];

        foreach ($html->find('img') as $img) {
            $i++;
            $src = $img->getAttribute('src');
            // фильтрую от ненужных фоток
            if (strlen($src) > 50) {
                $item[] = $img->getAttribute('src');
            }
        }
        $arrResult['IMAGES'] = serialize($item);

        $html->clear(); // подчищаем за собой
        unset($html);
        //vd($arrResult);
        return $arrResult;
}

// Помечаю что url обработан - добавляю текущее время
function addDtProces($id){
    $mysqli = Connect();
    $query = "UPDATE `url` SET `ID`=NULL,`url`= NULL,`DtAdded`=NULL,`DtProcessed`= time(),`DtUpdated`= time(),`Status`=NULL,`SourceID`=NULL,`House_ID`=NULL,`Source`= NULL WHERE 'ID'= $id";
    $result = $mysqli->query($query);
    if($result){
        echo 'ID='. $id .'Updated time!';
    }else{
        echo ' ERROR Updated time!';
    }

    return;

}

// Довабляю данные в таблицу Нouses
function AddUrlToTableHouses($ArrHouses){
    $mysqli = Connect();
    //vd($ArrHouses);
    $СaseNumber = isset($ArrHouses['СaseNumber']) ? trim($ArrHouses['СaseNumber']) : NULL;
    $Address = isset($ArrHouses['Address']) ? $ArrHouses['Address'] : NULL ;
    $Bed_bath = isset($ArrHouses['Bed/Bath']) ? $ArrHouses['Bed/Bath'] : NULL;
    $Total_rooms = $ArrHouses['Total Rooms'];
    $Square_Feet = $ArrHouses['Square Feet'];
    $Year = $ArrHouses['Year'];
    $Type = $ArrHouses['Housing Type'];
    $Number_of_story = $ArrHouses['Number of Stories'];
    $HOA_Fees = $ArrHouses['HOA Fees'];
    $Revitalization = $ArrHouses['Revitalization Area'];
    $Parking= $ArrHouses['Parking'];
    $Patio_Deck= $ArrHouses['Patio/Deck'];
    $Listing= $ArrHouses['LISTING'];
    $Agent= $ArrHouses['AGENT'];
    $Manager = $ArrHouses['MANAGER'];
    $Broker = $ArrHouses['BROKER'];
    $Images = $ArrHouses['IMAGES'];


        // Поиск дубликата

        $query = "SELECT * FROM `houses` WHERE `СaseNumber` = '" . $СaseNumber  . "'";
        $result = $mysqli->query($query);$count = $result->num_rows;
        if ($count > 0) {
            echo 'It is dublicate.skip it.It is allredy in bd.<br /> ';

        } else {
            echo 'WAU! It is a new record!';
            $query = "INSERT INTO `houses`(`ID`, `СaseNumber`, `Address`, `Bed_bath`, `Total_rooms`, `Square_Feet`, `Year`, `Type`, `Number_of_story`, `HOA_Fees`, `Revitalization`, `Parking`, `Patio_Deck`,`Listing`, `Agent`, `Manager`, `Broker`, `images`)
                                   VALUES (NULL,  '" . $СaseNumber . "','" . $Address . "','" . $Bed_bath . "','" . $Total_rooms . "','" . $Square_Feet . "','" . $Year . "','" . $Type . "','" . $Number_of_story . "','" . $HOA_Fees. "','" . $Revitalization. "','" . $Parking. "','" . $Patio_Deck. "','" . $Listing. "','" . $Agent. "','" . $Manager. "','" . $Broker. "','" . $Images. "')";
            $result = $mysqli->query($query);
            if ($result == 'true') {
                echo 'ok- added<br />';
            } else {
                echo 'error!!!<br />';
            }
        }

}


$arrSoursUrl = [
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=AK&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=AL&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=AR&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=AS&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=AZ&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=CA&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=CO&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=CT&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=DE&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=FL&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=GA&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=HI&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=IA&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=ID&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=IL&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=IN&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',
    'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=&sState=KS&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&indoorAmenities=&outdoorAmenities=&housingType=&stories=&parking=&propertyAge=&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sPageSize=100&sLanguage=ENGLISH',

];
?>