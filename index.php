<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to the cities of the world  page!</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=yes">
        <!--style>
            @import url("css/flexbox.css");
        </style-->
        <link rel='stylesheet' type="text/css" href="css/flexbox.css" />
        <link rel='stylesheet' type="text/css" href="css/flexboxprint.css" media="print" />
        <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            let countryPopulation = 0;
            function getCountryID() {
                if (!document.getElementById)//does it support javascript?
                    return;

                let country_ID = document.getElementById("country_id");
                //console.log("You chose  the  country_id of:" + country_ID.value);
                countryPopulation=0;
                return country_ID;
            }
            function getCityID() {

                let city = document.getElementById("city");
                //console.log(" You chose the city: " + city.value);
                return;

            }
            $(document).ready(function () {
                
                /*Second dynamic dropdown list depending on selection of first 
                 https://github.com/durgesh-sahani/dependent-drop-down-php-mysql-ajax
                 https://www.youtube.com/watch?v=HRV2zEIMBqU */
                $("#country_id").change(function () {
                    let aid = $("#country_id").val();
                     
                    $.ajax({
                        url: 'data.php',
                        method: 'post',
                        data: 'aid=' + aid
                    }).done(function (city) {
                        // console.log(city);
                        city = JSON.parse(city);
                        $('#city').empty();
                        city.forEach(function (city) {

                            $('#city').append('<option value=' + city.id + '>' + city.id + " " + city.city_ascii + " " + city.population + '</option>');
                            /*countryPopulation+=parseInt(city.population);
                            if (countryPopulation==0) countryPopulation=1; */
                            //console.log(countryPopulation);
                        })
                    })
                })//end of country_id change function

                $("#city").change(function () {
                    let city = $("#city").val();
                    $.ajax({
                        url: 'data.php',
                        method: 'post',
                        data: 'city=' + city
                    }).done(function (city) {
                        //console.log(city);
                        city = JSON.parse(city);
                      
                        $('#details').empty();
                        $('#details').append("<p> Χώρα Country: " + city.country + " <p>Κωδικός ISO3: " + city.iso3 + "<p> Πόλη City: " + city.city_ascii +
                        "<p> Περιοχή Region: " + city.region + "<p> Πληθυσμός Population: " + city.population  + "<p> Ποσοστό επί συνολικού πληθυσμού Percentage of total population:  " /*+ Number.parseFloat(city.population*100/countryPopulation).toFixed(2)*/
                       +  city.percentage +" % <p>Long. " + city.lng + "<p> Lat. " + city.lat + "</p>");
                      
                        $('#map').empty();
                        $('#map').append("<iframe width='100%' height='auto'  src='https://maps.google.com/maps?q=" + city.lat + ',' + city.lng + "&hl=en&z=14&amp;output=embed' </iframe>");
                    })
                })//end of city change function
            })
            
        </script>

    </head>
    <body>
        <header role="banner">
            <h1>Welcome to the cities of the world!</h1>
        </header>
        <section id='main'>
            <div id='wrapper'>
                <!-- start of flex container -->
                <main id='home'>
                    <section>
                        <h2></h2>
                        <div id="input">
                            <legend>
                                <fieldset>
                                    <form action="" method="post" name="personal" id="personal" >
                                        <p>
                                            <label for="country_id">Επιλέξτε την  χώρα: <br/>Select the country you wish:</label>
                                        </p>
                                        <p>  
                                            <select type="number" name="country_id"  required id="country_id" onChange="getCountryID();" size="" max='1'>
                                                <option selected value="" disabled>Select from list  below </option>/*<?php echo isset($_POST['id']) ? $_POST['id'] : '0' ?>*/
                                                <?php
                                                require_once 'data.php';
                                                $countries = loadCountries(); /* CALL FUNCTION IN DATA.PHP */
                                                foreach ($countries as $country) {
                                                    echo "<option id='" . $country['id'] . "' value='" . $country['iso3'] . "'>" . $country['id'] . "
                                                     " . $country['country'] . " " . $country['iso3'] . " Population " . $country['total'] . " </option>";
                                                }
                                               
                                                ?>

                                            </select> 
                                        </p>
                                        <p>
                                            <label for="city">Επιλέξτε την  πόλη:<br/>
                                             Select the city you wish: </label>
                                        </p>
                                        <p>
                                            <select type="number" name="city" required  id="city"  size="5" max="1" onChange="getCityID();">
                                                <option selected value='0'disabled>Select from list  below </option>

                                            </select> 
                                         </div>
                                    </form>
                            </legend>
                            </fieldset>
                    </section>
                    <div class="central" ><!--align= 'center'-->
                        <h2>Χάρτης Map</h2>

                        <div style="width: 100%">
                            <!--iframe width="100%" height="auto" 
                              src="https://maps.google.com/maps?output=embed&amp;width=100%&amp;height=600&amp;hl=en&amp;q=35.6850,139.7514&amp;ie=UTF8&amp;t=&amp;z=16&amp;iwloc=B"
                              frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe-->
                            <div id='map' name='map' >

                            </div>

                            <br />
                            <!--small>
                              <a 
                               href="https://maps.google.com/maps?q='+data.lat+','+data.lon+'&hl=en;z=14&amp;output=embed" 
                               style="color:#0000FF;text-align:left" 
                               target="_blank"
                              >
                                See map bigger
                              </a>
                            </small-->

                        </div>
                    </div>
                </main>
                <div id='rightside'>
                    <h2>Πληροφορίες Details:</h2><!--?php require_once 'data.php'; echo $total; ?-->
                    <!--dynamically assigned-->
                    <span id="details" name="details">

                    </span>
                </div>
            </div><!-- end of flex container -->
        </section>

        <footer>
            <h6>Copyright 2021</h6>
        </footer>
    </body>
</html>