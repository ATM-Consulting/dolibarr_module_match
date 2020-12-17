<?php
global $db, $player_all, $winner_all, $looser_all;

dol_include_once('core/class/html.form.class.php');

$formUser = new Form($db);
$listAllPlayer = $formUser->select_dolusers($player_all, 'player_all', 1);
$listAllWinner = $formUser->select_dolusers($winner_all, 'winner_all', 1);
$listAllLooser = $formUser->select_dolusers($looser_all, 'looser_all', 1);

print '<div id="filter_match"><table><tr class="liste_titre"><td colspan="100%">';
print '<label for="player_all">Joueur</label>';
print $listAllPlayer;
print '&nbsp;&nbsp;<label for="winner_all">Gagnant</label>';
print $listAllWinner;
print '&nbsp;&nbsp;<label for="looser_all">Perdant</label>';
print $listAllLooser;
print '</td></tr></table></div>';
?>
<script type="text/javascript">
    $(document).ready(function() {
        $divFilter = $("#filter_match tr");
        $("#match thead").prepend($divFilter)
    })
</script>