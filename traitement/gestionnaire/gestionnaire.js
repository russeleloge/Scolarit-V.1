$(document).ready(() => {
    $('#tbl_student').DataTable();
    // $('[data-toggle="popover"]').popover();
    $(".myPopovers").popover({
        title: fetchData,
        html: true,
        placement: 'left',
    });

    $('.edit_app').on('dblclick', () => {
        console.log("bonsoir")
    })

    $('.edit_app').on('keydown', () => {
        console.log("bonjour")
    })

    // 
    $('#key').on('click', () => {
        var test = 0;
        var ancien_mdp = $('#ancien_mdp').val();
        var nouveau_mdp = $('#nouveau_mdp').val();
        var confirm_mdp = $('#confirm_mdp').val();

        if (ancien_mdp == '' || ancien_mdp.length < 4) {
            $('#ancien_mdp').addClass('is-invalid')
            test = 0
        } else {
            $('#ancien_mdp').removeClass('is-invalid')
            test = 1
        }

        if (nouveau_mdp == '' || nouveau_mdp.length < 4) {
            $('#nouveau_mdp').addClass('is-invalid')
            test = 0
        } else {
            $('#nouveau_mdp').removeClass('is-invalid')
        }

        if (confirm_mdp == '' || confirm_mdp.length < 4) {
            $('#confirm_mdp').addClass('is-invalid')
            test = 0
        } else {
            $('#confirm_mdp').removeClass('is-invalid')
        }

        if (nouveau_mdp != confirm_mdp) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                toast: true,
                title: 'Le nouveau et la confimation doivent etre identiques !',
                showConfirmButton: false,
                timer: 3000
            })
            test = 0;
        }

        if (test == 1) {
            $.ajax({
                url: "../traitement/gestionnaire/transfert_change_password.php",
                method: 'POST',
                data: {
                    nouveau_mdp: nouveau_mdp,
                    ancien_mdp: ancien_mdp,
                    confirm_mdp: confirm_mdp,
                },
                success: function (data) {
                    if (data == "true") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            toast: true,
                            title: 'Mot de passe modifié avec succès !',
                            showConfirmButton: false,
                            timer: 3000
                        })
                        setTimeout(() => {
                            document.location.href = 'accueil.php'
                        }, 3000)
                    }
                    else if (data == "mdp") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            toast: true,
                            title: 'Ancien mot de passe éroné !',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                }
            });
        }
    })


    function fetchData() {

        var id = $(this).attr("id")

        $.ajax({
            url: "../traitement/gestionnaire/transfert_fetch_popover.php",
            method: 'POST',
            async: false,
            data: { id: id },
            success: function (data) {
                fetch_data = data;
            }
        });
        return fetch_data;
    }
})


var control = 0;
$(document).ready(() => {
    $('#paie').on('click', (e) => {
        e.preventDefault;
        control += 1;
        if (control % 2 == 0) {
            $('#content_mtn_ins').css('display', 'none');
            $('#mod_pay').css('display', 'none');
        } else {
            $('#mod_pay').css('display', 'block');
            $('#content_mtn_ins').css('display', 'block');
        }
    })
})

function insert_student() {
    $(document).ready((e) => {
        e.preventDefault
        var matricule = Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
        var nom = $('#nom').val()
        var prenom = $('#prenom').val()
        var sexe = $('#sexe').val()
        var dates = $('#date').val()
        var tel = $('#tel').val()
        var cni = $('#cni').val()
        var saison = $('#saison').val()
        var select = $('#intervalle').val()
        var mtn_ins = $('#mtn_ins').val()
        var mod_pay = $('#mod_pay').val()
        var recup_select;
        var formation = $('#formation').val()
        var myImage = $('#myImage').val()
        // var img = $('#photo')[0].files
        var test = 0
        var format = /^(\d{1,2}\/){2}\d{4}$/
        // console.log(imgrep)

        if (nom == '' || nom.length < 4) {
            $('#nom').addClass('is-invalid')
            test = 0
        } else {
            $('#nom').removeClass('is-invalid')
            test = 1
        }

        if (prenom == '' || prenom.length < 4) {
            $('#prenom').addClass('is-invalid')
            test = 0
        } else {
            $('#prenom').removeClass('is-invalid')
            if (test == 0) { }
        }

        if (sexe == '') {
            $('#sexe').addClass('is-invalid')
            test = 0
        } else {
            $('#sexe').removeClass('is-invalid')
            if (test == 0) { }
        }
        if (myImage == '') {
            $('#myImage').addClass('is-invalid')
            test = 0
        } else {
            $('#myImage').removeClass('is-invalid')
            if (test == 0) { }
        }

        // Date.parse(dates > 01/01/2005
        if (new Date(dates) == 'Invalid Date') {
            $('#date').addClass('is-invalid')
            // dates = dates.format("DD-MM-YYYY");
            test = 0
        } else {
            $('#date').removeClass('is-invalid')
            if (test == 0) { }
        }

        if (tel == '' || tel.length != 9) {
            $('#tel').addClass('is-invalid')
            test = 0
        } else {
            $('#tel').removeClass('is-invalid')
            if (test == 0) { }
        }

        if (cni == '') {
            $('#cni').addClass('is-invalid')
            test = 0
        } else {
            $('#cni').removeClass('is-invalid')
            if (test == 0) { }
        }

        if (saison == '') {
            $('#saison').addClass('is-invalid')
            test = 0
        } else {
            $('#saison').removeClass('is-invalid')
            if (test == 0) { }
        }
        // ##########################################
        if (select == '') {
            $('#intervalle').addClass('is-invalid')
            test = 0
        } else {
            $('#intervalle').removeClass('is-invalid')
            recup_select = select.split('/')
            if (test == 0) { }
        }
        // 
        if (formation == '') {
            $('#formation').addClass('is-invalid')
            test = 0
        } else {
            $('#formation').removeClass('is-invalid')
            if (test == 0) { }
        }
        // 
        if ((control % 2) != 0) {
            if (mtn_ins == '') {
                $('#mtn_ins').addClass('is-invalid')
                test = 0
            } else {
                $('#mtn_ins').removeClass('is-invalid')
                if (test == 0) { }
            }

            if (mod_pay == '') {
                $('#mod_pay').addClass('is-invalid')
                test = 0
            } else {
                $('#mod_pay').removeClass('is-invalid')
                if (test == 0) { }
            }
        }

        if (test == 1) {
            // var form_data = new FormData()
            // form_data.append('photo', img[0])
            $.ajax({
                url: '../traitement/gestionnaire/transfert_insert_student.php',
                method: 'POST',
                data: {
                    nom: nom,
                    prenom: prenom,
                    sexe: sexe,
                    dates: dates,
                    tel: tel,
                    formation: formation,
                    saison: saison,
                    cni: cni,
                    matricule: matricule,
                    mtn_ins: mtn_ins,
                    mod_pay: mod_pay,
                    control: control,
                    debut: recup_select[0],
                    fin: recup_select[1],
                    myImage:myImage,
                    // form_data,
                    // imgrep: imgrep
                },
                success: function (data) {
                    if (data == 'true') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            toast: true,
                            title: 'Nouveau apprenant ajouté avec succès !',
                            showConfirmButton: false,
                            timer: 2900
                        })
                        setTimeout(() => {
                        //document.location.href = 'accueil.php'
                        document.location.href = '../traitement/gestionnaire/recu/contenu.php'
                        }, 3000)
                        setTimeout(() => {
                            document.location.href = 'accueil.php'
                            }, 7000)
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            toast: true,
                            title: 'Un ou plusieurs de vos paramètre(s) doivent etre uniques !',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                    // console.log($data)
                }
            })

            let form_data = new FormData();
            let img = $("#myImage")[0].files;
            form_data.append('my_image', img[0]);

            $.ajax({
                url: '../traitement/gestionnaire/upload.php',
                method: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                success: function (data) {
                    // if (data == "true") setTimeout(() => {
                    //     document.location.href = 'accueil.php'
                    // }, 3000)
                }
            })
        }
    })
}



function search_indice() {
    $('table').remove()
    var critere = $('#critere').val()

    $.ajax({
        url: '../traitement/gestionnaire/transfert_recherche_critere.php',
        method: 'POST',
        data: {
            critere: critere
        },
        success: function (data) {
            console.log("Good job")
            var content = data.split('*');
            var row = $('<tr></tr>');
            var nom = $('<td class="d-flex align-items-center"> <div class=""> <img src = "./images/IMG-6293ec7c815b19.21173460.jpg" style = "width: 70px; height: 70px; border-radius:50%;"> </div> <div class="pl-3 email"><span>' + content[0] + '</span> <span>' + content[1] + '</span></div></td>')
            row.append(nom);
            document.createElement('table');
            $('.table').append("<table><thead><tr><th>Hello</th></tr></thead></table>");

        }
    })
}

function affiche_intervalle() {

    // parent.removeClass("inactive").addClass("active");
    $('#bloc_naissance').removeClass('col-md-12').addClass("col-md-6");
    $('#bloc_intervalle').css('display', 'block');

    $('#intervalle option').filter(function () {
        return this.value;
    }).remove();

    var saison = $('#saison').val()
    var formation = $('#formation').val()
    var option1 = document.querySelector('.option1')
    var bloc_option = document.querySelector('#bloc_intervalle')
    $.ajax({
        url: '../traitement/gestionnaire/transfert_select_intervalle.php',
        method: 'POST',
        data: {
            saison: saison,
            formation: formation
        },
        success: function (data) {
            // console.log(data)
            var table = data.split('*')
            var j = 0;
            // var select = $('#intervalle')
            var count = (table.length - 1) / 2;
            for (let i = 0; i < count; i++) {
                j += i;
                var option = "<option>" + table[j] + " / " + table[j + 1] + "</option";
                $('#intervalle').append(option)
                j += 1;
            }
        }
    })
}


function active_pay_app(id) {

    $('#id_appr').val(id);
    var nnom = $('#' + id).children('img[data-target=img_app]').attr('src');
    var nom = $.trim(nnom);

    $('#img_appr').attr("src", nom);

}


function active_modif_app(id) {

    var recup_id = id
    // var nnom = $('#' + id).children('td[data-target=nom_formation]').text();
    // var nom = $.trim(nnom);
    // var serie = $('#' + id).children('td[data-target=serie_formation]').text();
    // $('#nom_formation').val(nom);
    console.log(id)

    var nom = $('#nn' + id).children('span[data-target=nomApp]').text();
    console.log(nom)
    $('#nom').val(nom)

}


$("#reset").bind("click", function () {
    $("input[type=text], number, select, file").val("");
});


function eff_vers() {
    var id_appr = $('#id_appr').val();
    var mtn_verse = $('#mtn_verse').val();
    var typ_pay = $('#type_paye').val();
    var controler = 0

    if (mtn_verse == '') {
        $('#mtn_verse').addClass('is-invalid')
        controler = 0
    } else {
        $('#mtn_verse').removeClass('is-invalid')
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
                    url: '../traitement/gestionnaire/transfert_mtn_verse.php',
                    method: 'POST',
                    data: {
                        id_appr: id_appr,
                        mtn_verse: mtn_verse,
                        typ_pay: typ_pay
                    },
                    success: function (data) {

                        if (data == "true") {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                toast: true,
                                title: "Transaction éffectuée avec succès !",
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
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                toast: true,
                                title: "Montant élevé !",
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    }
                })
            }
        })
    }


}


function active_recu(id){
    console.log(id)
    $.ajax({
        url: '../traitement/gestionnaire/transfert_id_session.php',
        method: 'POST',
        data: {
            id: id
        },
        success: function(data){
            document.location.href = '../traitement/gestionnaire/recu/contenu.php'
        }
})
}
