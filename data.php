<?php

require_once 'dbconnect.php';
$total;
$countryID;
$db = new DbConnect;
$conn = $db->connect();

if (isset($_POST['countryID'])) {

    $countryID = $_POST['countryID']; //
   
    $stmt = $conn->prepare("SELECT  
                                    cities.aa,
                                    cities.id,
                                    cities.city_ascii as city_ascii ,
                                    countries.country as country,        
                                    countries.iso3 as iso3,
                                    cities.population as population,
                                   cities.lng,
                                  cities.lat
		                               
    FROM      countries,cities
     WHERE  cities.country_iso3 = countries.iso3
     AND cities.country_iso3 = '$countryID'
     ORDER BY city_ascii
     LIMIT 7500 "
    );
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

     echo json_encode($rows);
      
}
if (isset($_POST['city'])) {
    /* Get selected city details */
    $cityID = $_POST['city'];

    $stmt1 = $conn->prepare("SELECT  
                                 cities.aa,
                                 cities.id,
                                 cities.city_ascii as city_ascii ,
                                 countries.country as country,        
                                 countries.iso3 as iso3,
		                            cities.population as population,
                                cities.lng,
                                cities.lat,
	                              cities.admin_name as region,
                                 total, IFNULL(population*100/total,0) percentage
                              
    FROM 
      countries,cities/*,regions*/,  (
    SELECT
        countries.country,
      countries.iso3 iso3,
        SUM(cities.population) total
    FROM
        cities
    INNER JOIN countries ON countries.iso3 = cities.country_iso3
    GROUP BY
        countries.id
) total
     WHERE  cities.country_iso3 = countries.iso3
     /*AND cities.region_id=regions.aa*/
       AND cities.id = '$cityID'
         AND total.iso3 = cities.country_iso3
        LIMIT 1 "
    );
    $stmt1->execute();
    //$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $row = $stmt1->fetch();
    echo json_encode($row);

    $stmt = null; // doing this is mandatory for connection to get closed
    $conn = null;
}

function loadCountries() {
    $db = new DbConnect;
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT countries.id,
		                              countries.country,
                                 countries.iso3,
                                 SUM(cities.population) total
		FROM countries,cities
    WHERE cities.country_iso3 = countries.iso3		
    GROUP BY countries.id");
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null; // doing this is mandatory for connection to get closed
    $conn = null;

    return $countries;
}
/*Unused verification use only*/
function cityPopulationAsPercentageOfTotalPopulationForACountry($iso3) {
    $db = new DbConnect;
    $conn = $db->connect();
    $stmt = $conn->prepare("SELECT
    cities.city_ascii, cities.population population, cities.country_iso3, total, IFNULL(population*100/total,0) percentage
   FROM
    cities, countries,  (
    SELECT
        countries.country,
      countries.iso3 iso3,
        SUM(cities.population) total
    FROM
        cities
    INNER JOIN countries ON countries.iso3 = cities.country_iso3
    GROUP BY
        countries.id
) total
 WHERE countries.iso3 = cities.country_iso3
 AND total.iso3 = cities.country_iso3
 AND cities.country_iso3 = '$iso3'
 ORDER BY country_iso3
 LIMIT 100
         ");
    $stmt->execute();
    $total= $stmt->fetch();
 
    $stmt = null; // doing this is mandatory for connection to get closed
    $conn = null;

    return reset($total);
}

?>
