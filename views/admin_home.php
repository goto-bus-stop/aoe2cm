<?php
$presets = $this->presets;
$tournaments = $this->tournaments;
?>

<div class="header">
    <a href="<?= ROOTDIR ?>/logout" class="text-primary shadowbutton"><?= _("Logout") ?></a>
    <a href="<?= ROOTDIR ?>/maintenance" class="text-primary shadowbutton"><?= _("Maintenance") ?></a>
</div>

<div class="admin content">
    <div class="double-outer-border">
        <div class="double-inner-border">
            <div id="newpreset">
                <p>
                    <form action="preset-new" method="get">
                        <span class="text-primary"><?= _("New preset") ?>:</span>
                        <input type="text" name="name" size="64" class="inset-input text-primary"/>
                        <input type="submit" name="new" value="Create" class="shadowbutton text-primary"/>
                    </form>
                </p>
            </div>

            <table class="presets pure-table pure-table-horizontal">
                <thead>
                    <tr>
                        <td><?= _("Name") ?></td>
                        <td><?= _("Enabled") ?></td>
                        <td><?= _("Actions") ?></td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($presets as $preset) { ?>
                    <tr>
                        <td><?= $preset->name ?></td>
                        <td><?= $preset->state == Preset::PRESET_ENABLED ? _("true") : _("false") ?></td>
                        <td>
                            <a href="<?= ROOTDIR ?>/preset-history?p=<?= $preset->id ?>"
                                class="text-primary shadowbutton">history</a>
                            <a href="<?= ROOTDIR ?>/preset-edit?p=<?= $preset->id ?>"
                                class="text-primary shadowbutton">edit</a>
                            <?php if ($preset->state == Preset::PRESET_DISABLED) { ?>
                                <a href="<?= ROOTDIR ?>/preset-enable?e=1&p=<?= $preset->id ?>"
                                    class="text-primary shadowbutton">enable</a>
                            <?php } else { ?>
                                <a href="<?= ROOTDIR ?>/preset-enable?e=0&p=<?= $preset->id ?>"
                                    class="text-primary shadowbutton">disable</a>
                            <?php } ?>
                            <a href="<?= ROOTDIR ?>/preset-delete?p=<?= $preset->id ?>"
                                class="text-primary shadowbutton">delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="double-outer-border" style="display: none">
        <div class="double-inner-border">
            <div id="newtournament">
                <p>
                    <form action="tournament-new" method="post">
                        <span class="text-primary"><?= _("New tournament") ?>:</span><br />
                        <span><?= _("Name") ?>:</span>
                        <input type="text" name="name" size="64" class="inset-input text-primary"/><br />
                        <span><?= _("Description") ?>:</span>
                        <textarea rows="5" cols="50" name="description" maxlength="255"
                            class="inset-input text-primary"></textarea><br />
                        <input type="submit" name="new" value="Create" class="shadowbutton text-primary"/>
                    </form>
                </p>
            </div>

            <table class="tournaments pure-table pure-table-horizontal">
                <thead>
                    <tr>
                        <td><?= _("Name") ?></td>
                        <td><?= _("Query") ?></td>
                        <td><?= _("Actions") ?></td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tournaments as $tournament) { ?>
                    <tr>
                        <td><?= $tournament->name ?></td>
                        <td><?= htmlentities($tournament->description) ?></td>
                        <td>
                            <a href="<?= ROOTDIR ?>/tournament-delete?id=<?= $tournament->id ?>"
                                class="text-primary shadowbutton">delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
