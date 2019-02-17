
<div class="content admin box preset-edit">

<div id="preset-name" class="centered text-primary info-card"><?php echo $preset->name; ?></div> 

<div id="preset-description" class="centered text-primary info-card"><?php echo $preset->description; ?></div> 

<div class="home_card box">
    <h2><?php echo _("Recent Drafts"); ?></h2>
    <table class="pure-table pure-table-horizontal recent-drafts">
        <thead>
            <tr>
               <td>Code</td>
               <td>Players</td>
               <td>Started (GMT)</td>
               <td>Actions</td> 
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($last_drafts as $draft) {
                ?>
            <tr>
                <td class='recent-title'><?php echo $draft->code; ?></td>
                <td class='recent-users'>
                    <?php echo $draft->players[0]->name." "._(" vs ")." ".$draft->players[1]->name; ?>
                </td>
                <td class='recent-date'><?php echo $draft->date_started_str; ?></td>
                <td class='recent-action'><?php
                    echo "<a href=\"".ROOTDIR."/spectate?code=".$draft->code."\" class=\"text-primary shadowbutton\">";
                if ($draft->isDone()) {
                    echo _("Watch");
                } else {
                    echo _("Watch Live");
                }
                    echo "</a>";
                ?>
                </td>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

</div>
