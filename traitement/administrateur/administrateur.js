$(document).ready(() => {
    $('#tbl_user').DataTable();
    $('#tbl_user1').DataTable();
    $('#tbl_user2').DataTable();
    $('#tbl_user3').DataTable();
})

function insert_user() {
    $(document).ready(() => {
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var sexe = $('#sexe').val();
        var dates = $('#date').val();
        var tel1 = $('#tel1').val();
        var tel2 = $('#tel2').val();
        var cni = $('#cni').val();
        var username = $('#user').val();
        var test = 0;
        var format = /^(\d{1,2}\/){2}\d{4}$/;

        if (nom == "" || nom.length < 4) {
            $('#nom').addClass('is-invalid');
            test = 0;
        } else {
            $('#nom').removeClass('is-invalid');
            test = 1;
        }

        if (prenom == "" || prenom.length < 4) {
            $('#prenom').addClass('is-invalid');
            test = 0;
        } else {
            $('#prenom').removeClass('is-invalid');
        }

        if (sexe == "") {
            $('#sexe').addClass('is-invalid');
            test = 0;
        } else {
            $('#sexe').removeClass('is-invalid');
        }
        // Date.parse(dates > 01/01/2005
        if (new Date(dates) == "Invalid Date") {
            $('#date').addClass('is-invalid');
            test = 0;
        } else {
            $('#date').removeClass('is-invalid');
        }

        if (tel1 == "" || tel1.length != 9) {
            $('#tel1').addClass('is-invalid');
            test = 0;
        } else {
            $('#tel1').removeClass('is-invalid');
        }

        if (cni == "") {
            $('#cni').addClass('is-invalid');
            test = 0;
        } else {
            $('#cni').removeClass('is-invalid');
        }

        if (username == "" || username.length < 4) {
            $('#user').addClass('is-invalid');
            test = 0;
        } else {
            $('#user').removeClass('is-invalid');
        }

        if (test == 1) {
            $.ajax({
                url: '../traitement/administrateur/transfert_insert_user.php',
                method: 'POST',
                data: {
                    nom: nom,
                    prenom: prenom,
                    sexe: sexe,
                    dates: dates,
                    tel1: tel1,
                    tel2: tel2,
                    cni: cni,
                    username: username,
                },
                success: function (data) {
                    if (data == "true") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            toast: true,
                            title: "Nouveau utilisateur ajouté avec succès !",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        setTimeout(() => {
                            document.location.href = 'admin.php'
                        }, 3000)
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            toast: true,
                            title: "Un ou plusieurs de vos paramètre(s) doivent etre uniques !",
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                    console.log(data);
                    // console.log(data);
                }
            })
        }


        // Swal.fire({

        //     title: "Nouveau enregistrement",
        //     text: "Un nouveau caissier va etre enregistré !",
        //     icon: "warning",
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Continuer',
        //     cancelButtonText: 'Annuler'
        // }).then((result) => {
        //     if (result.isConfirmed) {

        //     }
        // })
    })
}

function closeOther1() {
    $('.table_user').css('display', 'none');
    $('.table_tarif').css('display', 'none');
    $('.table_formation').css('display', 'block');
    $('#adUser').css('display', 'none');
    $('#adTarif').css('display', 'none');
    $('#adFormation').css('display', 'block');
    $.ajax({
        url: '../traitement/administrateur/transfert_session_formation.php',
    })
}

function closeOther0() {
    $('.table_user').css('display', 'block');
    $('.table_tarif').css('display', 'none');
    $('.table_formation').css('display', 'none');
    $('#adUser').css('display', 'block');
    $('#adTarif').css('display', 'none');
    $('#adFormation').css('display', 'none');
    $.ajax({
        url: '../traitement/administrateur/transfert_session_gestionnaire.php',
    })
}

function closeOther2() {
    $('.table_tarif').css('display', 'block');
    $('.table_user').css('display', 'none');
    $('.table_formation').css('display', 'none');
    $('#adUser').css('display', 'none');
    $('#adTarif').css('display', 'block');
    $('#adFormation').css('display', 'none');
    $.ajax({
        url: '../traitement/administrateur/transfert_session_tarif.php',
    })
}



function part_insert_form() {
    var nom = $('#nom_formation').val();
    var serie = $('#serie_formation').val();
    var test = 0;

    if (nom == "" || nom.length < 4) {
        $('#nom_formation').addClass('is-invalid');
        test = 0;
    } else {
        $('#nom_formation').removeClass('is-invalid');
        test = 1;
    }
    if (serie == "") {
        $('#serie_formation').addClass('is-invalid');
        test = 0;
    } else {
        $('#serie_formation').removeClass('is-invalid');
    }

    return test;
}

function insert_formation() {

    var nom = $('#nom_formation').val();
    var serie = $('#serie_formation').val();
    if (part_insert_form() == 1) {
        $.ajax({
            url: '../traitement/administrateur/transfert_insert_formation.php',
            method: 'POST',
            data: {
                nom: nom,
                serie: serie,
            },
            success: function (data) {
                if (data == "true") {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        toast: true,
                        title: "Nouvelle formation ajoutée avec succès !",
                        showConfirmButton: false,
                        timer: 3000
                    })
                    setTimeout(() => {
                        document.location.href = 'admin.php'
                    }, 3000)
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        toast: true,
                        title: "Cette formation existe déja !",
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            }
        })
    }
}





$('#adFormation').on('click', () => {
    $('#insert_form').css('display', 'block');
    $('#update_form').css('display', 'none');
})


// #######################     Quand je voulais modifier une formation       ##############################

// function edit_formation() {

//     var id = $('#id_formation').val();
//     var nom = $('#nom_formation').val();
//     var serie = $('#serie_formation').val();

//     if (part_insert_form() == 1) {  
//         $.ajax({
//             url: '../traitement/administrateur/transfert_update_formation.php',
//             method: 'POST',
//             data: {
//                 id: id,
//                 nom: nom,
//                 serie: serie,
//             },
//             success: function (data) {
//                 if (data == "true") {
//                     Swal.fire({
//                         position: 'top-end',
//                         icon: 'success',
//                         toast: true,
//                         title: "Formation modifiée avec succès !",
//                         showConfirmButton: false,
//                         timer: 3000
//                     })
//                 } else {
//                     Swal.fire({
//                         position: 'top-end',
//                         icon: 'error',
//                         toast: true,
//                         title: "Cette formation existe déja !",
//                         showConfirmButton: false,
//                         timer: 3000
//                     })
//                 }
//             }
//         })
//     }
// }

// function active_edit_formation(id) {

//     $('#id_formation').val(id);
//     $('#insert_form').css('display', 'none');
//     $('#update_form').css('display', 'block');
//     var nnom = $('#' + id).children('td[data-target=nom_formation]').text();
//     var nom = $.trim(nnom);
//     var serie = $('#' + id).children('td[data-target=serie_formation]').text();
//     $('#nom_formation').val(nom);
//     $('#option1').text(serie);
//     $('#option1').val(serie);

// }

function insert_tarif() {

    var serie = $('#serie_form').val();
    var saison = $('#saison').val();
    var debut = $('#debut').val();
    var fin = $('#fin').val();
    var tranche1 = $('#tranche1').val();
    var tranche2 = $('#tranche2').val();
    var tranche3 = $('#tranche3').val();
    var date1 = $('#date_lim_1').val();
    var date2 = $('#date_lim_2').val();
    var date3 = $('#date_lim_3').val();
    var test = 0;

    if (serie == "") {
        $('#serie_form').addClass('is-invalid');
        test = 0;
    } else {
        $('#serie_form').removeClass('is-invalid');
        test = 1;
    }

    if (saison == "") {
        $('#saison').addClass('is-invalid');
        test = 0;
    } else {
        $('#saison').removeClass('is-invalid');
    }

    if (tranche1 == "" || tranche1.lenght < 5) {
        $('#tranche1').addClass('is-invalid');
        test = 0;
    } else {
        $('#tranche1').removeClass('is-invalid');
    }
    if (tranche2 == "" || tranche2.lenght < 5) {
        $('#tranche2').addClass('is-invalid');
        test = 0;
    } else {
        $('#tranche2').removeClass('is-invalid');
    }
    if (tranche3.lenght < 5 || tranche3 == "") {
        $('#tranche3').addClass('is-invalid');
        test = 0;
    } else {
        $('#tranche3').removeClass('is-invalid');
    }
    if (new Date(debut) == "Invalid Date") {
        $('#debut').addClass('is-invalid');
        test = 0;
    } else {
        $('#debut').removeClass('is-invalid');
    }
    if (new Date(fin) == "Invalid Date") {
        $('#fin').addClass('is-invalid');
        test = 0;
    } else {
        $('#fin').removeClass('is-invalid');
    }
    if (debut > fin) {
        test = 2;
    }
    // #######################
    if (new Date(date1) == "Invalid Date") {
        $('#date_lim_1').addClass('is-invalid');
        test = 0;
    } else {
        $('#date_lim_1').removeClass('is-invalid');
    }
    if (new Date(date2) == "Invalid Date") {
        $('#date_lim_2').addClass('is-invalid');
        test = 0;
    } else {
        $('#date_lim_2').removeClass('is-invalid');
    }
    if (new Date(date3) == "Invalid Date") {
        $('#date_lim_3').addClass('is-invalid');
        test = 0;
    } else {
        $('#date_lim_3').removeClass('is-invalid');
    }

    if (date1 > date2 || date2 > date3 || date1 > date3) {
        test = 3;
    }

    // ########################

    if (test == 2) {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            toast: true,
            title: "Période incorrecte !",
            showConfirmButton: false,
            timer: 3000
        })
    } else if (test == 3) {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            toast: true,
            title: "Mauvaise repartition des dates limites !",
            showConfirmButton: false,
            timer: 3000
        })
    } else if (test == 1) {
        var total = parseInt(tranche1) + parseInt(tranche2) + parseInt(tranche3);
        // $('#total').val(total);
        $.ajax({
            url: '../traitement/administrateur/transfert_insert_tarif.php',
            method: 'POST',
            data: {
                serie: serie,
                saison: saison,
                debut: debut,
                fin: fin,
                tranche1: tranche1,
                tranche2: tranche2,
                tranche3: tranche3,
                date1: date1,
                date2: date2,
                date3: date3,
                total: total
            },
            success: function (data) {
                if (data == "true") {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        toast: true,
                        title: "Nouveau tarif ajouté avec succès !",
                        showConfirmButton: false,
                        timer: 3000
                    })
                    setTimeout(() => {
                        document.location.href = 'admin.php'
                    }, 3000)
                } else if (data == "duplication") {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        toast: true,
                        title: "Un ou plusieurs de vos paramètre(s) doivent etre uniques !",
                        showConfirmButton: false,
                        timer: 3000
                    })
                }

            }
        })
    }
}



// $(document).ready(
// $('.custom-control-input').click((e) => {
// e.preventDefault();
function control_check(id) {
    var ss = $(id).attr("id");
    var recup_id = (ss.slice(ss.lastIndexOf('c') - 1));
    var control = '';
    if ($(id).prop('checked') === true) {
        control = "oui";
    } else {
        control = "non";
    }

    $.ajax({
        url: '../traitement/administrateur/transfert_update_autorisation.php',
        method: 'POST',
        data: {
            recup_id: recup_id,
            control: control,
        },
        success: function (data) {

        }
    })
}
// )

function reset_password(id) {
    var ss = $(id).attr("id");
    var recup_id = (ss.slice(ss.lastIndexOf('x') - 1));
    var ssa = $(id).attr("class");
    var recup_nom = (ssa.slice(ssa.lastIndexOf('btn-danger') + 10));
    $(recup_nom).css('font-weight', 'bold');

    Swal.fire({

        title: "Réinitialisation",
        text: "Voulez-vous réinitialiser le mot de passe de " + recup_nom + " ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continuer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../traitement/administrateur/transfert_reset_password.php',
                method: 'POST',
                data: {
                    recup_id: recup_id,
                },
                success: function (data) {

                    if (data == "true") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            toast: true,
                            title: "Réinitialisation éffectuée avec succès !",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        setTimeout(() => {
                            document.location.href = 'admin.php'
                        }, 3000)
                    } else {

                    }
                }
            })
        }
    })
}


function sup_formation(id) {
    var ss = $(id).attr("id");
    var recup_id = (ss.slice(ss.lastIndexOf('y') - 1));
    var ssa = $(id).attr("class");
    var recup_nom = (ssa.slice(ssa.lastIndexOf('btn-danger') + 10));

    Swal.fire({

        title: "Suppression",
        text: "Voulez-vous supprimer la formation " + recup_nom + " ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continuer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../traitement/administrateur/transfert_delete_formation.php',
                method: 'POST',
                data: {
                    recup_id: recup_id,
                },
                success: function (data) {

                    if (data == "true") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            toast: true,
                            title: "Formation suprimée avec succès !",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        setTimeout(() => {
                            document.location.href = 'admin.php'
                        }, 3000)
                    } else {

                    }
                }
            })
        }
    })
}

function affiche_res() {

    if ($('#critere').val() == 'Gestionnaire') {

        $('#opt2').remove();
        $('#opt2').remove();
        $('#opt2').remove();

        // $('#bloc_selection').css('opacity', '0,3');
        $('.containt1').removeClass('col-md-5')
        $('.containt1').addClass('col-md-10')
        $('.containt2').css('display', 'none')
        $('.contenu1').css('display', 'block')
        $('.contenu2').css('display', 'none')


        $('.contenu1').removeClass('col-md-5')
        $('.contenu1').addClass('col-md-10')
        $('.contenu2').css('display', 'none')
        $('.contenu3').css('display', 'none')

        $.ajax({
            url: '../traitement/administrateur/transfert_select_elt.php',
            method: 'POST',
            data: {},
            success: function (data) {
                // console.log(data)
                var table = data.split('*')
                var j = 0;
                // var select = $('#intervalle')
                var count = (table.length - 1) / 2;
                for (let i = 0; i < count; i++) {
                    j += i;
                    var option = "<option id='opt2'>" + table[j] + " " + table[j + 1] + "</option>";
                    $('#selection').append(option)
                    j += 1;
                }
            }
        })
    }
    else if ($('#critere').val() == 'Scolarité') {

        $('#opt').remove();
        $('#opt').remove();
        $('#opt').remove();


        $('.contenu1').removeClass('col-md-10')
        $('.contenu1').addClass('col-md-5')
        // $('.contenu2').css('display','block')


        $('.containt1').removeClass('col-md-10')
        $('.containt1').addClass('col-md-5')
        $('.containt2').css('display', 'block')
        $('.contenu1').css('display', 'none')
        $('.contenu2').css('display', 'block')
        $('.contenu3').css('display', 'block')

        $.ajax({
            url: '../traitement/administrateur/transfert_select_elt1.php',
            method: 'POST',
            data: {},
            success: function (data) {
                // console.log(data)
                var table = data.split('*')
                for (let i = 0; i < table.length - 1; i++) {

                    var option = "<option id='opt'>" + table[i] + "</option>";
                    $('#selection1').append(option)

                }
            }
        })

    }
    else {
        $('.containt1').removeClass('col-md-5')
        $('.containt1').addClass('col-md-10')
        $('.containt2').css('display', 'none')
        $('.contenu1').css('display', 'block')
        $('.contenu2').css('display', 'none')

        $('.contenu1').removeClass('col-md-5')
        $('.contenu1').addClass('col-md-10')
        $('.contenu2').css('display', 'none')
        $('.contenu3').css('display', 'none')

        $('.bloc_selection').css('display', 'none')
    }
}


function active_edi_pay(id) {

    var montant = $('#xc' + id).children('div[data-target=montant]').text();
    montant1 = montant.replace(/ /g, '')
    $('#mt_verse').val(montant1)
    var type = $('#xcc' + id).children('div[data-target=type]').text();
    console.log(type)
    $('#type_paye').val(type)

    $('#id_vers').val(id)

    var nnom = $('#' + id).children('img[data-target=img_app]').attr('src');
    var nom = $.trim(nnom);

    $('#img_appr').attr("src", nom);

}

function modif_vers() {

    var id_vers = $('#id_vers').val();
    var mtn_verse = $('#mt_verse').val();
    var typ_pay = $('#type_paye').val();

    if (mtn_verse == '') {
        $('#mt_verse').addClass('is-invalid')
        controler = 0
    } else {
        $('#mt_verse').removeClass('is-invalid')
        controler = 1
    }

    if (controler == 1) {
        Swal.fire({

            title: "Paiement",
            text: "Voulez-vous confirmer l'opération de " + mtn_verse + " ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continuer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../traitement/administrateur/transfert_modif_mtn_verse.php',
                    method: 'POST',
                    data: {
                        id_vers: id_vers,
                        mtn_verse: mtn_verse,
                        typ_pay: typ_pay
                    },
                    success: function (data) {
                        // console.log(data)
                        // if (data == "true") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            toast: true,
                            title: "Modification éffectuée avec succès !",
                            showConfirmButton: false,
                            timer: 2900
                        })
                        setTimeout(() => {
                            // document.location.href = 'accueil.php'
                            document.location.href = '../traitement/gestionnaire/recu/contenu.php'
                        }, 3000)
                        setTimeout(() => {
                            document.location.href = 'accueil.php'
                            // document.location.href = '../traitement/gestionnaire/recu/contenu.php'
                        }, 7000)
                        // } else {
                        //     Swal.fire({
                        //         position: 'top-end',
                        //         icon: 'error',
                        //         toast: true,
                        //         title: "Montant élevé !",
                        //         showConfirmButton: false,
                        //         timer: 3000
                        //     })
                        // }
                    }
                })
            }
        })
    }

}

$("#reset").bind("click", function () {
    $("input[type=text], number, select, file").val("");
});