
// window.onload = function() {
//     var choix = "<?php echo isset($_SESSION['reponse_choisie']) ? $_SESSION['reponse_choisie'] : ''; ?>";
//     var reponsesIncorrectes = <?php echo isset($_SESSION['reponses_incorrectes']) ? json_encode($_SESSION['reponses_incorrectes']) : '[]'; ?>;

//     if (choix !== '') {
//         var reponseChoisie = document.querySelector('input[value="' + choix + '"]');
//         if (reponseChoisie) {
//             reponseChoisie.parentElement.style.display = 'none';
//         }
//     }

//     reponsesIncorrectes.forEach(function(reponse) {
//         var reponseIncorrecte = document.querySelector('input[value="' + reponse + '"]');
//         if (reponseIncorrecte) {
//             reponseIncorrecte.parentElement.style.display = 'none';
//         }
//     });
// };

