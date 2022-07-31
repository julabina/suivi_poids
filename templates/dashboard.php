<?php $title = 'dashboard'; ?>
<?php ob_start(); ?>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    let weightListArr = [], listArr = [];
    let phpDate, dateSplit, dateMod, dateDecr, dayForFinalArr, daySplit, tempArr, overlayPara;

    <?php foreach($userWeightList as $row): ?>
        tempArr = [];
        phpDate = "<?= $row->recordDate; ?>";
        dateSplit = phpDate.split('-');
        dateDecr = (dateSplit[1] /* - 1 */) + "";
        if(dateDecr.length === 1) {
            dateMod = '0' + dateDecr;
        } else {
            dateMod = dateDecr;
        }
        daySplit = dateSplit[2].split(' ');
        dayForFinalArr = dateSplit[0] + ", " + dateMod + ", " + daySplit[0];
        dayForOverlay = daySplit[0] + '/' + dateMod + '/' + dateSplit[0];
    
        overlayPara = <?= $row->weight; ?> + 'kg le ' + dayForOverlay;

        tempArr.push(new Date(dayForFinalArr));
        tempArr.push(<?= $row->weight; ?>);
        tempArr.push(overlayPara);

        listArr.push(tempArr);
    <?php endforeach; ?>      
    weightListArr = listArr;                
</script>
<script>

        google.charts.load('current', {
            packages: ['corechart']
        });
        
        const drawChart = () => {
            const dataChart = new google.visualization.DataTable();
            dataChart.addColumn('date', 'lastWeight');
            dataChart.addColumn('number', 'Poids');
            dataChart.addColumn({type: 'string', role: 'tooltip'});                      
            dataChart.addRows(
                weightListArr
            );
                
            const chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                
            const options = {
                title: 'Vos derniers poids ajoutés',
                hAxis: {
                    title: 'Temps',  
                    titleTextStyle: {color: '#333'},
                    format: 'dd/MM/yy',
                    gridlines: {count: 10} 
                },
                vAxis: {
                    minValue: 0,
                    gridlines: {color: 'none'}
                },
                colors: ['#0C2E8A'],
                pointSize: 5,
                pointShape: 'square',
                backgroundColor: '#FDFDFD',
                /* legend: 'none', */
            };
            
            // Instantiate and draw the chart.
            chart.draw(dataChart, options);
        }
        
        google.charts.setOnLoadCallback(drawChart);
        </script>

<?php $contentHead = ob_get_clean(); ?>

<?php
    if(!isset($_SESSION['name']) || !isset($_SESSION['user']) || !isset($_SESSION['userId']) || !isset($_SESSION['size']) || (!isset($_SESSION['sexe']) || ($_SESSION['sexe'] !== "man" && $_SESSION['sexe'] !== "woman")) || !isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
        header('Location: /suivi_poids/login');
    }
    ?>

<?php ob_start(); ?>

<script>
    const successfulObj = () => {
        const objCard = document.querySelector('.dash__obj');
        objCard.classList.add('dash__obj--success');
    };
</script>

<header class="dashHeader">
    <div class="header">
        <a href="/suivi_poids/"><h1>TITRE DU SITE</h1></a>
        <div class="header__connected">
            <a class="header__connected__logoutBtn" href="/suivi_poids/logout">Se deconnecter</a>
            <a href="/suivi_poids/profil"><div class="header__connected__userProfil">
                <img src="../suivi_poids/assets/user.svg" alt="user icon">
            </div></a>
        </div>
    </div>
</header>

<main class="dash">
    <!-- section infos begin -->
    <section class="dash__infos">
        <h2>Tableau de bord</h2>
        <div class="dash__infos__errorCont">
            <?php if(isset($_GET['err']) && $_GET['err'] === "addW"): ?>
                <p>- Une erreur est survenu, impossible d'ajouter un nouveau poids.</p>
            <?php endif; ?>
        </div>
        <div class="dash__infos__cont">
            <div onClick="handleModal()" class="dash__infos__cont__infoBox">
                <h4>Votre poids au <?= $userData->recordDate; ?></h4>
                <h3><?= $userData->weight; ?></h3>
                <p>cliquer pour ajouter un nouveau poids.</p>
            </div>
            <a href="/suivi_poids/imc"><div class="dash__infos__cont__infoBox">
                <h4>Votre IMC</h4>
                <h3><?= floor($userData->imc); ?></h3>
                <p></p>
            </div></a>
            <a href="/suivi_poids/img"><div class="dash__infos__cont__infoBox">
                <h4>Votre IMG</h4>
                <h3><?= $userData->img; ?>%</h3>
                <p></p>
            </div></a>
        </div>
        <a href="/suivi_poids/objectifs"><div class="dash__obj">
            <div class="dash__obj__content">
                <?php if($userData->weight_goal === NULL && $userData->imc_goal === NULL && $userData->img_goal === NULL): ?>
                    <h3>Vous pouvez définir un objectif ici !!</h3>
                <!-- if goal is weight -->
                <?php elseif($userData->weight_goal !== null): ?>
                    <?php if($userData->weight <= $userData->weight_goal): ?>
                        <?= "<script> successfulObj(); </script>"; ?>
                        <h4>Objectif Atteint, Félicitation !</h4>
                    <?php else: ?>
                        <h4>Votre objectif actuel</h4>
                    <?php endif; ?>
                    <h3>Atteindre le poids de <?= $userData->weight_goal; ?> kg</h3>
                <!-- if goal is imc -->
                <?php elseif($userData->imc_goal !== null): ?>
                    <?php if($userData->imc <= $userData->imc_goal): ?>
                        <?= "<script> successfulObj(); </script>"; ?>
                        <h4>Objectif Atteint, Félicitation !</h4>
                    <?php else: ?>
                        <h4>Votre objectif actuel</h4>
                    <?php endif; ?>
                    <h3>Atteindre un IMC de <?= $userData->imc_goal; ?></h3>
                <!-- if goal is img -->
                <?php elseif($userData->img_goal !== null): ?>
                    <?php if($userData->img <= $userData->img_goal): ?>
                        <?= "<script> successfulObj(); </script>"; ?>
                        <h4>Objectif Atteint, Félicitation !</h4>
                    <?php else: ?>
                        <h4>Votre objectif actuel</h4>
                    <?php endif; ?>
                    <h3>Atteindre un IMG de <?= $userData->img_goal; ?>%</h3>
                <?php endif; ?>
                <!-- <p>Phrase en rapport a la reussite ou non</p> -->
            </div>
            <p>Cliquer pour définir un nouvel objectif !</p>
        </div></a>
    </section>
    <!-- section infos end -->

    <!-- section graph begin -->
    <section class='dash__graph'>
        <div class="dash__graph__mainCont">
            <div class="dash__graph__mainCont__tabs">
                <div onClick="handleGraph('line')" class="dash__graph__mainCont__tabs__tab dash__graph__mainCont__tabs__tab--active"><p>Graphique</p></div>
                <div onClick="handleGraph('list')" class="dash__graph__mainCont__tabs__tab"><p>Liste</p></div>
            </div>
            <div class="dash__graph__mainCont__graphNav">
                <div class="dash__graph__mainCont__graphNav__graphSelectType">
                        <?php
                            $weightYearArr = [];
                            $years = [];
                            foreach ($userWeightList as $userWeight) {
                                $weightYear = explode('-', $userWeight->recordDate);
                                $weightYearArr[] = $weightYear[0];
                            }
                            $uniqueWeightYear = array_reverse(array_unique($weightYearArr));
                            foreach($uniqueWeightYear as $year) {
                                $years[] = $year;
                            }
                        ?>
                    <select name="graphSelectType" id="graphSelectType" onChange="changeType()">
                        <option value="byTime1">1 mois</option>
                        <option value="byTime2">2 mois</option>
                        <option value="byTime3">3 mois</option>
                        <option value="byTime6">6 mois</option>
                        <option value="byTime">1 ans</option>
                        <?php foreach($years as $year): ?>
                            <option value="byYear<?= $year; ?>">Année <?= $year; ?></option>
                        <?php endforeach; ?>
                        <option value="byAll">Tout</option>
                    </select>
                </div>
            </div>
            <div class="dash__graph__mainCont__graph" id="chart_div">
            </div>
            <div class="dash__graph__mainCont__graph dash__graph__mainCont__graph--bot dash__graph__mainCont__graph--hidden">
                <?php foreach($userWeightList as $weight): ?>
                    <div class="dash__graph__mainCont__graph__row">
                        <div class="dash__graph__mainCont__graph__row_"><p><?= date('d/m/Y',strtotime($weight->recordDate)); ?></p></div>
                        <div class="dash__graph__mainCont__graph__row_"><p><?= $weight->weight; ?></p></div>
                        <div class="dash__graph__mainCont__graph__row_"><p><?= $weight->bmi; ?></p></div>
                        <div class="dash__graph__mainCont__graph__row_"><p><?= $weight->bfp; ?></p></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- section graph end -->
    <button onClick="tes7t()">ok</button>
</main>

<!-- modal add weight begin -->
<section class="dashAddWeight dashAddWeight--hidden">
    <div class="dashAddWeight__modal">
        <p onClick="handleModal()" class="dashAddWeight__modal__close">X</p>
        <?php $currentDate = date('d-m-Y'); ?>
        <?php if($userData->recordDate === $currentDate): ?>
            <h2>Vous ne pouvez ajouter qu'une date par jour.</h2>
            <?php else: ?>
                <form class="dashAddWeight__modal__form" action="/suivi_poids/addWeight" method="post">
                    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                    <label for="addWeightInput">Ajouter un nouveau poids</label>
                    <p class="dashAddWeight__modal__form__error"></p>
                <input type="number" name="addWeight" id="addWeightInput">
                <input onClick="verifyAddWeight()" class="dashAddWeight__modal__form__submitBtn" type="button" value="Ajouter">
            </form>
            <?php endif; ?>
    </div>
</section>
<!-- modal add weight end -->

<script>  
    const modal = document.querySelector(".dashAddWeight");
    currentDate = new Date();

    const verifyAddWeight = () => {   
        const addWeight = document.getElementById("addWeightInput");
        const modalErrorCont = document.querySelector(".dashAddWeight__modal__form__error");
        const modalForm = document.querySelector(".dashAddWeight__modal__form");
        let modalError = "";
        modalErrorCont.textContent = "";
    
        if(addWeight.value === '') {
            modalError = '- Le poids ne doit pas etre vide.';
        } else if(addWeight.value < 30) {
            modalError = '- Le poids doit etre superieur à 30 kg.';
        } else if(addWeight.value > 260) {
            modalError = '- Le poids doit etre inférieur à 260 kg.';
        } else if(!addWeight.value.match(/^[0-9]*$/)) {
            modalError = '- Le poids ne doit contenir que des chiffres.';
        }
    
        if(modalError !== "") {
            return modalErrorCont.textContent = modalError;
        }

        modalForm.submit();
        handleModal();
    };

    const handleModal = () => {
        if(modal.classList.contains('dashAddWeight--hidden')) {
            modal.classList.remove("dashAddWeight--hidden");
        } else {
            modal.classList.add("dashAddWeight--hidden");
        }
    };

    const handleGraph = (graph) => {
        const tabs = document.querySelectorAll('.dash__graph__mainCont__tabs__tab');
        const graphs = document.querySelectorAll('.dash__graph__mainCont__graph');
        const graphNav = document.querySelector('.dash__graph__mainCont__graphNav');

        if(graph === "list") {
            tabs[1].classList.add('dash__graph__mainCont__tabs__tab--active');
            tabs[0].classList.remove('dash__graph__mainCont__tabs__tab--active');
            graphs[0].classList.add('dash__graph__mainCont__graph--hidden');
            graphs[1].classList.remove('dash__graph__mainCont__graph--hidden');
            graphNav.classList.add('dash__graph__mainCont__graphNav--hidden');
        }else if(graph === "line") {
            tabs[0].classList.add('dash__graph__mainCont__tabs__tab--active');
            tabs[1].classList.remove('dash__graph__mainCont__tabs__tab--active');
            graphs[1].classList.add('dash__graph__mainCont__graph--hidden');
            graphs[0].classList.remove('dash__graph__mainCont__graph--hidden');
            graphNav.classList.remove('dash__graph__mainCont__graphNav--hidden');
        } 
    };

    const changeType = () => {

        const selectType = document.getElementById('graphSelectType');

        option = selectType.value;

        if(option === "byTime1") {
            calculForSelect(2592000000);
        } else if(option === "byTime2") {
            calculForSelect(5184000000);
        } else if(option === "byTime3") {
            calculForSelect(7776000000); 
        } else if(option === "byTime6") {
            calculForSelect(15552000000);  
        } else if(option === "byTime") {
            calculForSelect(31104000000);
        } else if(option === "byAll") {
            weightListArr = listArr;
            google.charts.setOnLoadCallback(drawChart);
        } else {
            slicedOption = option.slice(6);
           
            filteredWeightListArr = listArr.filter(el => {
                splitedDate = el[2].split('/');
                if(splitedDate[2] === slicedOption) {
                    return el;
                }
            });

            weightListArr = filteredWeightListArr;
            google.charts.setOnLoadCallback(drawChart);
        }
        
    }

    const calculForSelect = (time) => {
        filteredListArr = listArr.filter(el => {
            result = currentDate.getTime() - el[0].getTime();
            if(result < time) {
                return el;
            }
        });

        weightListArr = filteredListArr;
        google.charts.setOnLoadCallback(drawChart);
    }

    changeType();

</script>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php'); ?>