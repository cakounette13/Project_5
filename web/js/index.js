$(document).ready(function () {
    $.fn.datepicker.dates['fr'] = {
        months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthsShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        daysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        daysMin: ['Di', 'Lu', 'Mar', 'Me', 'Je', 'Ve', 'Sa']
    };
    $('#datepicker')
        .datepicker({
            language: 'fr',
            weekStart: 1,
            autoclose: true,
            format: 'dd/mm/yyyy',
            endDate: 'NOW',
            todayHighlight: true
        });
});
