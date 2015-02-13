$texte_de = array(
"Montag", "Dienstag", "Mittwoch", "Donnerstag",
"Freitag", "Samstag", "Sonntag", "Januar",
"Februar", "März", "April", "May",
"Juni", "July", "August", "September",
"October", "November", "December",
);
$texte_fr = array(
"Lundi", "Mardi", "Mercredi", "Jeudi",
"Vendredi", "Samedi", "Dimanche", "Janvier",
"Février", "Mars", "Avril", "Mai",
"Juin", "Juillet", "Août", "Septembre",
"Octobre", "Novembre", "Décembre",


$texte_de = array(
"Mon", "Die", "Mit", "Thu", "Don", "Sam", "Son",
"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
"Aug", "Sep", "Oct", "Nov", "Dec",
);
$texte_fr = array(
"Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim",
"Jan", "Fev", "Mar", "Avr", "Mai", "Jui",
"Jui", "Aou;", "Sep", "Oct", "Nov", "Dec",
);


function convertDayEnToFr($_day) {
if ($_day == 'Monday' || $_day == 'Mon') {
return 'Montag';
}
if ($_day == 'monday' || $_day == 'mon') {
return 'montag';
}
if ($_day == 'Tuesday' || $_day == 'Tue') {
return 'Donnerstag';
}
if ($_day == 'tuesday' || $_day == 'tue') {
return 'donnerstag';
}
if ($_day == 'Wednesday' || $_day == 'Wed') {
return 'Mittwoch';
}
if ($_day == 'wednesday' || $_day == 'wed') {
return 'mittwoch';
}
if ($_day == 'Thursday' || $_day == 'Thu') {
return 'Donnerstag';
}
if ($_day == 'thursday' || $_day == 'thu') {
return 'Donnerstag';
}
if ($_day == 'Friday' || $_day == 'Fri') {
return 'Freitag';
}
if ($_day == 'friday' || $_day == 'fri') {
return 'freitag';
}
if ($_day == 'Saturday' || $_day == 'Sat') {
return 'Samstag';
}
if ($_day == 'saturday' || $_day == 'sat') {
return 'samstag';
}
if ($_day == 'Sunday' || $_day == 'Sun') {
return 'Sonntag';
}
if ($_day == 'sunday' || $_day == 'sun') {
return 'Sonntag';
}
