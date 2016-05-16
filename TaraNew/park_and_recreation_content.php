<div class="row" >
    <div class="ip-main ">
        <div class="ip-content">
            <div class="row"  ng-controller="controller">
                <div class="col-md-3">
                    <ul class="nav-parks" ng-init="tab = 1">
                        <li><a href="#" ng-click="tab = 1">Tašmajdan</a></li>
                        <li><a href ng-click="tab = 2">Botanička bašta</a></li>
                        <li><a href ng-click="tab = 3">Veliko ratno ostrvo</a></li>
                        <li><a href ng-click="tab = 4">Karađorđev park</a></li>
                        <li><a href ng-click="tab = 5">Topčiderski park</a></li>
                        <li><a href ng-click="tab = 6">Košutnjak</a></li>
                        <li><a href ng-click="tab = 7">Ada Ciganlija</a></li>
                        <li><a href ng-click="tab = 8">Avala / Toranj</a></li>
                    </ul>
                </div>
                <div class="col-md-9">

                        <div class="parks-content-list" ng-show="tab === 1">
                            <img src="images/parkovi/tasmajdan.jpg" class="parks-img-content"/>
                            <h3>Crkva Svetog Marka je pravoslavna crkva i nalazi se na Tašmajdanu.</h3>
                            <p>Građena je od 1931. do 1940. godine u blizini stare crkve, koju je podigao knez Miloš 1835. godine, jer se pojavila potreba vernika za većom bogomoljom. Građena
                            je po planovima arhitekata Petra i Branka Krstića, a oblikovana u duhu arhitekture srpko-vizantijkog stila. </p><p>Po svom izgledu
                            crkva najviše podseća na manastir Gračanicu. Stara crkva je, na žalost, srušena u nemačkom bombardovanju Srbije 1941.
                            godine, a njeni ostaci su uklonjeni 1942. godine. Pored toga što crkva Svetog Marka lepotom svoje arhitekture ne ostavlja
                             nikoga ravnodušnim, ona je i jako važan istorijski spomenik Srbije.</p> <p>Naime, crkva se nalazi na prostoru gde je pročitan sultanov hatišerif 1830. godine, u kom se priznaje autonomija Srbije unutar turske carevine. Crkva takođe čuva i sarkofag sa kostima cara Dušana Silnog, koje su prenesene iz njegove zadužbine manastira svetog Arhanđela kod Prizrena.
                            U crkvi su takođe sahranjeni i ostaci patrijarha Germana Đorića, kao i poslednji kralj iz dinastije Obrenovića –
                            kralj Aleksandar Obrenović i njegova supruga Draga Mašin.</p> <p>U crkvi se čuva i jedna od najbogatijih zbirki srpskih ikona
                            iz 18. i 19. veka. Bogata istorija, lepota arhitekture, kao i lepota Tašmajdana i parka koji okružuje crkvu, su više nego
                            dobar razlog da je posetite.</p>



                        </div>
                        <div class="parks-content-list" ng-show="tab === 2">

                            <img src="images/parkovi/botanicka_basta.png" class="parks-img-content"/>
                            <h3>Botanička bašta Jevremovac se nalazi u Beogradu u Takovskoj ulici 43.</h3>
                            <p>Botanička bašta Jevremovac se nalazi u Beogradu u Takovskoj ulici 43. Prostire se na povšini od 4,8183 hektara i na njoj se nalazi oko 1000 biljnih vrsta, staklena bašta od
                                500 kvadratnih metara, koja je podignuta 1892. godine, zgrade Instituta za botaniku sa izuzetno vrednim herbarijumom, osnovanim 1860. godine, i bibliotekom sa oko 13000 bibliotečkih
                                jedinica osnovanom 1853. godine.</p>
                            <p>Univerzitetska botanička bašta je nastala 1874. godine, i nalazila se uz obalu Dunava, ali je ona uništena 1888. godine usled poplave.
                                Tada je kralj Milan Obrenović poklonio gradu imanje na Paliluli, pod uslovom da Botanička bašta bude nazvana „Jevremovac“, u znak sećanja na njegovog dedu Jevrema Obrenovića, od koga je nasledio imanje.</p>
                            <p>1892. godine je podignuta staklena bašta na površini od 500 kvadratnih metara, koja se sastoji od dva krila spojenih centralnom kupolom. Izgrađena je iz delova koji su preneti iz Drezdena, i u to vreme je bila među najlepšima na Balkanu</p>
                        <p>U 1994. godini je Zavod za zaštitu prirode Srbije dao predlog, kojim je 1995. godine Uredbom Vlade Republike Srbije Botanička bašta Jevremovac proglašena za Spomenik prirode od velikog značaja.</p>
                            <p>Od 18. maja 2004. godine je otvoren i Japanski vrt, koji poseduje kolekciju dalekoistočne flore.</p>
                            <p>Ljubitelji botanike mogu uživati u svim čarima koje Botanička bašta Jevremovac nudi, a često se organizuju i razne izložbe, promocije, manifestacije a moguće je rezervisati i privatni aranžman.</p>
                        </div>

                        <div class="parks-content-list" ng-show="tab === 3">
                            <img src="images/parkovi/v_ratno_ostrvo.png" class="parks-img-content"/>
                            <h3> Veliko ratno ostrvo</h3>
                            <p>Veliko ratno ostrvo, površine 200 hektara, nalazi se na ušću reke Save u Dunav, u trouglu između Beogradske tvrđave i Zemuna.
                                Zbog netaknute prirode i nastanjenih različitih vrsta ptica ovo ostrvo je zaštićeno 2005. godine i proglašeno prirodnim rezervatom.
                                Na severnom delu ostrva nalazi se plaža Lido koja je u letnjoj sezoni otvorena za posetioce i turiste.</p>
                        </div>

                        <div class="parks-content-list" ng-show="tab === 4">
                            <img src="images/parkovi/karadjordjev_park.png" class="parks-img-content"/>
                        <h3>Karađorđev park</h3>
                           <p>Karađorđev park se nalazi na Vračaru, u blizini hrama Svetog Save. Poznat je po tome što je 1806. godine bio jedan od logora ustaničke vojske i na tom prostoru su sahranjeni ustanici koji su
                               poginuli u Prvom srpskom ustanku protiv Turaka.</p>
                            <p>Takođe, u parku se može videti kamena ploča koja seća na tragičnu pogibiju građana pri prvom bombardovanju nemačke u Drugom svetskom ratu.</p>

                        </div>
                        <div class="parks-content-list" ng-show="tab === 5">
                            <img src="images/parkovi/topc_park.png" class="parks-img-content"/>
                            <h3>Topčiderski park</h3>
                            <p>Topčiderski park je prvi i verovatno najlepši park u Beogradu. Nalazi se na Topčiderskom brdu i prostire se na 15 hektara, udaljen svega 5 kilometara od centra Beograda. </p>
                            <p>Ova pošumljena oblast je nekada bila turski vojni kamp u kojem su se pravili topovi, otuda je i dobila naziv Topčiderski park. Danas se u sklopu parka nalazi rezidencija kneza Miloša Obrenovića, poznata kao Milošev konak. </p>
                            <p>Kroz Topčiderski park protiče Topčiderska reka, a kako ga krase visoko drveće, romantične klupe, kamene stazice i malo jezero sa drvenim mostićem, veoma čest prizor su mladenci koji prave umetničke fotografije u ovoj bajkovitoj atmosferi.</p>
                            <p>Zaštitni znak parka je veliki platan ispred konaka kneza Miloša. Drvo je među najstarijima u Evropi, star je preko 170 godina, a visok preko 35 metara i zaštićen kao prirodna retkost.</p>
                        </div>
                        <div class="parks-content-list" ng-show="tab === 6">
                            <img src="images/parkovi/kosutnjak.png" class="parks-img-content"/>
                            <h3>Košutnjak</h3>
                            <p>Takođe važan deo Topčidera je i park-šuma Košutnjak.</p>
                            <p>U vreme kneza Miloša, Košutnjak je predstavljao kraljevsko lovište i uzgajalište srna i košuta. Veruje se da je šuma dobila naziv po velikom broju košuta koje su tu živele. Košutnjak je bio ograđen drvenom ogradom, koja je uklonjena 1908. godine kada je Košutnjak dobio status narodnog dobra. Slobodno ubijanje divljači je za posledicu imalo potpuno istrebljenje košuta za samo nekoliko godina. U Košutnjaku je 1868. godine ubijen knez Mihailo Obrenović, i na tom mestu se sada nalazi ograđen proctor.</p>
                            <p>Dok je Topčiderski park ostao gotovo nepromenjen, Košutnjak se dosta razvio. U svojoj ponudi ima dosta staza za šetanje i trčanje, sprave za vežbanje, park za decu i piknik zone, a ne izostaje ni velika ponuda kafića i restorana.</p>
                        </div>

                        <div class="parks-content-list" ng-show="tab === 7">
                            <img src="images/parkovi/adac.png" class="parks-img-content"/>
                            <h3>Ada Ciganlija</h3>
                            <p>Ada Ciganlija je veštačko poluostrvo na reci Savi, udaljeno 5 km od centra Beograda. Pri spajanju sa rekom Savom formirano je jezero ukupne površine od 800 hektara, sa najvišom dubinom od 6 metara. Ada je postala veoma popularna među Beograđanima devedesetih godina prošlog veka, odakle je dobila naziv “Beogradsko more”. U letnjoj sezoni Ada Ciganlija ima preko 100.000 posetilaca dnevno. </p>
                            <p>Okolina jezera prekrivena je šljunkovitim plažama, zelenilom, parkovima, biciklističkim i pešačkim stazama, mnogobrojnim sportskim terenima, raznim vodenim atrakcijama i velikim brojem restorana, barova i klubova koji rade tokom cele godine. Ada Ciganlija je omiljeno gradsko mesto za opuštanje i rekreaciju.</p>
                        </div>
                        <div class="parks-content-list" ng-show="tab === 8">
                            <img src="images/parkovi/avala.png" class="parks-img-content"/>
                            <h3>Avala / Avalski toranj</h3>
                            <p>Nadomak Beograda, obrasla listopadnom i četinarskom šumom, nalazi se planina Avala. Na 511 metara nadmorske visine, Avala je jedno od omiljenih beogradskih izletišta ali i mesto sa bogatom istorijom. Prirodni kompleks planine Avale zaštićen je od 1859.</p>
                            <p>U srednjem veku na vrhu Avale nalazio se grad Žrnov sa istoimenom tvrđavom koji su u 15. veku osvojili Turci.</p>
                            <p>Duž puta za Avalu podignut je spomenik posvećen ruskim vojnicima poginulim u avionskoj nesreći 1964. godine kada se avion sa delegacijom sovjetske armije srušio na Avalu. </p>
                            <p>Na vrhu beogradske planine nalazi se mauzolej i spomenik Neznanom junaku, podignut 1938. godine, posvećen žrtvama iz Prvog svetskog rata.</p>
                            <p>Na Avali se nalazi i famozni Avalski toranj, visok 205 metara koji ga čine najvišom građevinom u Srbiji. Originalni toranj uništen je 1999. godine, tokom NATO bombardovanja. Toranj je ponovo podignut i otvoren 11 godina nakon bombardovanja. Na vrhu tornja nalazi se osmatračnica sa koje se pruža pogled na čitav Beograd i okolinu.</p>
                        </div>


                </div>
            </div>

        </div>
    </div>
</div>