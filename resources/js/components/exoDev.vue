<template>
    <div class="container col-sm-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col col-sm-12">
                    <h4 class="titleHeader">EXERCICE DE DEVELOPPEMENT EN LARAVEL ET VUE JS</h4><br/>
                    <fieldset  class="border-top border-right border-left border p-2" >
                        <legend class="w-auto legendApp"> APP COMPTABLE</legend>
                        <div class="card text-nowrap cardApp">
                            <div class="card-body cBody">
                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="rib"  class="control-label col-sm-4 calibri">Rib</label>
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-align-left"></i></span></div>
                                                    <select id="rib" v-model="rib" class="custom-select custom-select-sm" required>
                                                    <option value=""></option>
                                                    <option v-for="rib in getAllRib">{{rib}}</option>
                                                    </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-sm text-nowrap col-md-4 btnGetOpe hoverToRuby" @click="getOperation(rib,debut,fin)">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                            Afficher l'operation
                                            </button>
                                            <button type="button" class="btn btn-sm text-nowrap col-md-8 btnCalculSolde hoverToGrey" @click="calculSolde(rib,debut,fin)">
                                                        <i class="fa fa-calculator" aria-hidden="true"></i>
                                            Calcule Solde
                                            </button>

                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6 text-nowrap">
                                            <div class="input-group ">
                                                <label  class="control-label col-sm-4 calibri">Date debut</label>
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa fa-calendar"></i></span></div>
                                                    <input value="debut" id="debut" v-model="debut" type="date" class="form-control form-control-sm"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div v-show="statSolde">Le solde client du rib {{rib}} est de : <span style="font-weight: bold;">{{soldeClient}}</span></div>
                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6 text-nowrap">
                                            <div class="input-group ">
                                                <label  class="control-label col-sm-4 calibri">Date fin</label>
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa fa-calendar"></i></span></div>
                                                    <input value="fin" id="fin" v-model="fin" type="date" class="form-control form-control-sm"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </fieldset><br/>
                    <table class="table table-striped table-condensed table-hover table-bordered">
                        <thead class="headerTable text-nowrap text-center">
                            <tr>
                                <th style="width:20%;">RIB</th>
                                <th style="width:10%;">DATE</th>
                                <th style="width:20%;">LIBELLE</th>
                                <th style="width:10%;">RECETTE</th>
                                <th style="width:10%">DEPENSE</th>
                            </tr>
                        </thead>
                        <tbody class="bodyTable">
                            <tr v-for="dataClient in getDataClient">

                                <td>{{dataClient.RIB}}</td>
                                <td>{{dataClient.Date}}</td>
                                <td>{{dataClient.Libelle}}</td>
                                <td class="text-center" v-if="parseInt(dataClient.Montant) >0">{{dataClient.Montant}}</td>
                                <td class="text-center" v-else>0</td>
                                <td class="text-center" v-if="parseInt(dataClient.Montant) <0">{{dataClient.Montant}}</td>
                                <td class="text-center" v-else>0</td>

                            </tr>
                            <tr v-if="(getDataClient).length == '0'">
                                <td colspan="12" class="text-center">Aucun résultat</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-show="requiredRib" class="snackbarToast"><span> <i class="fa fa-warning"></i></span> Champ rib obligatoire</div>
    </div>
</template>
<script>
    export default {

        data:function(){
            return{
                getDataClient: [],
                reelDataClient: [],
                getAllRib: [],
                rib:'',
                debut:'',
                fin:'',
                soldeClient:'',
                statSolde:false,
                requiredRib:false,
            }
        },
        methods:{
             /*== Une méthode permet d'appeller l'API client et de retourner la liste des opérations  ==*/
            loadDataClient: function(){
                /*== Load API client ==*/
                axios.get('api/getDataClient')
                .then((response)=>{
                    /*== Affecter les données recu dans un tableau ==*/
                    var dataClient = response.data;
                    var DataClient =  dataClient.operations;
                     /*== Ordonnées les operations par date ==*/
                    DataClient.sort((a,b)=>b.Date.localeCompare(a.Date));
                     /*== Affecter les operations dans un tableau pour l'affichage sur interface graphique ==*/
                    this.getDataClient = DataClient;
                     /*== Affecter les donnée reel dans un tableau  ==*/
                    this.reelDataClient = DataClient;
                    var allRib=[];
                      /*== Filter les rib doublon ==*/
                    DataClient.forEach(function(item){
                        var rib =item['RIB'];
                        if(allRib.includes(rib)){
                            /*== Ne pas ajouter dans la liste le rib doublons ==*/

                        }else{
                            /*== Ajouter dans la liste le nouveau rib ==*/
                            allRib.push(rib);
                        }

                    });
                     /*== Affecter les rib sans doublon dans un tableau pour l'affichage graphique dans un select option des rib ==*/
                    this.getAllRib = allRib;

                })
                //Catch errors
                .catch(function(error){
                    console.log("error");
                });
            },
            /*== Une méthode permet, pour un RIB donné et selon un encadrement de date (date minimum, date maximum),
            de retourner la liste des opérations répondant à ces critères.==*/
            getOperation: function (rib,debut,fin) {
                  /*== Ne pas afficher la dernier affichage status solde ==*/
                this.statSolde = false;

                /*== Validation champ rib ==*/
                var statRequiredRib = document.getElementById('rib').validity.valid;
                if(statRequiredRib===false){
                    this.requiredRib = true;

                }else{
                    var dateDebut= new Date(debut);
                        dateDebut = ("0" + dateDebut.getDate()).slice(-2)  + "/" + ("0"+(dateDebut.getMonth()+1)).slice(-2) + "/" + dateDebut.getFullYear();
                    var dateFin= new Date(fin);
                        dateFin= ("0" + dateFin.getDate()).slice(-2)  + "/" + ("0"+(dateFin.getMonth()+1)).slice(-2) + "/" + dateFin.getFullYear();
                    var dCli = this.reelDataClient;

                    if(debut=="" && fin ==""){
                        /*== Filter les operations par rib donné ==*/
                        var filter = dCli.filter(d =>d.RIB ===rib);

                    }else{
                        /*== Filter les operations par rib donné et entre deux date ==*/
                        var filter = dCli.filter(d =>d.RIB ===rib && d.Date >= dateDebut && d.Date <= dateFin);

                    }
                    /*== Affecter les operations filter par rib donné et entre deux date dans un tableau pour l'affichage sur interface graphique ==*/
                    this.getDataClient = filter;

                }

            },
             /*== Une méthode permet, pour un RIB donné et selon un encadrement de date, de
                calculer le solde du compte sur la période (total des recettes - total des dépenses).==*/
            calculSolde: function (rib,debut,fin) {
                /*== Validation champ rib ==*/
                var statRequiredRib = document.getElementById('rib').validity.valid;
                if(statRequiredRib===false){
                    this.requiredRib = true;

                }else{
                    var dateDebut= new Date(debut);
                        dateDebut = ("0" + dateDebut.getDate()).slice(-2)  + "/" + ("0"+(dateDebut.getMonth()+1)).slice(-2) + "/" + dateDebut.getFullYear();
                    var dateFin= new Date(fin);
                        dateFin= ("0" + dateFin.getDate()).slice(-2)  + "/" + ("0"+(dateFin.getMonth()+1)).slice(-2) + "/" + dateFin.getFullYear();
                    var dCli = this.reelDataClient;
                    if(debut=="" && fin ==""){
                    /*== Filter les operations par rib donné ==*/
                    var filter = dCli.filter(d =>d.RIB ===rib);

                    }else{
                    /*== Filter les operations par rib donné et entre deux date ==*/
                    var filter = dCli.filter(d =>d.RIB ===rib && d.Date >= dateDebut && d.Date <= dateFin);
                    }
                    /*== Affecter les operations filter par rib donné et entre deux date dans un tableau pour l'affichage sur interface graphique ==*/
                    this.getDataClient = filter;

                    /*== Sommer les recettes et les dépenses pour un RIB donné sur la période ==*/
                    var recettes=0;
                    var depenses=0;
                    filter.forEach(el => {
                        var montant =parseFloat(el.Montant);
                        if(montant >0){
                            recettes += montant;
                        }
                        if(montant <0){
                            var depense = el.Montant;
                            depense = depense.replace("-","");
                            depenses += parseFloat(depense);;
                        }

                    });
                    /*==  Calculer le solde du compte pour un RIB donné sur la période (total des recettes - total des dépenses) ==*/
                    var solde = recettes - depenses;
                    /*==  Afficher le solde pour un RIB donné et selon un encadrement de date ==*/
                    this.soldeClient = solde;
                    this.statSolde = true;
                }
            },
        },
        mounted() {
            this.loadDataClient();
        },

    }
</script>

