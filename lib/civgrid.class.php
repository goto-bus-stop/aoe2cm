<?php

require_once 'models/draft.class.php';

class CivGrid
{
    public static $aoc_civs = [
        "aztecs",
        "britons",
        "byzantines",
        "celts",
        "chinese",
        "franks",
        "goths",
        "huns",
        "japanese",
        "koreans",
        "mayans",
        "mongols",
        "persians",
        "saracens",
        "spanish",
        "teutons",
        "turks",
        "vikings",
    ];

    public static $aof_civs = [
        "italians",
        "incas",
        "indians",
        "magyars",
        "slavs",
    ];

    public static $aoak_civs = [
        "berbers",
        "ethiopians",
        "malians",
        "portuguese",
    ];

    public static $aor_civs = [
        "burmese",
        "khmer",
        "malay",
        "vietnamese",
    ];

    private $aoe_version = Draft::AOE_VERSION_AOC;

    public function __construct($aoe_version = Draft::AOE_VERSION_AOC)
    {
        $this->aoe_version = $aoe_version;
    }

    public function setup_tooltips_js()
    {
?>
      $(".choice").each(function(index) {
         $(".choice").eq(index).tooltipster({
            theme: 'aoecm-tooltip',
            animation: 'fade',
            delay: 750,
            touchDevices: false,
            position: 'bottom',
            arrow: true,
            content: $('.civ-'+$(this).data('civ'))
         });
      });
<?php
    }

    public function setup_tooltips_data()
    {
        if($this->aoe_version > Draft::AOE_VERSION_AOC) {
            return $this->setup_dlc_tooltips_data();
        }

        return $this->setup_aoc_tooltips_data();
    }

    public function setup_aoc_tooltips_data()
    {
?>
<div id="#civ-bonuses" style="display: none">
<div class="civ-bonus civ-random">
   <span class="civ-title"><?php echo _("Unknown"); ?></span>
   <span><br /><?php echo _("Different meanings based on the situation."); ?></span>
   <ul>
      <li><?php echo _("Random civ - when you see this as badge, <br />it means one of the captains didn't pick in time and a random civilization was picked for him."); ?></li>
      <li><?php echo _("No ban - when you see this as banned, <br />it means no civilization got banned"); ?></li>
   </ul>

</div>
<div class="civ-bonus civ-aztecs">
   <span class="civ-title"><?php echo _("Aztecs"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry and Monk civilization"); ?></span>
   <ul>
      <li><?php echo _("Villagers carry +5"); ?></li>
      <li><?php /* xgettext:no-php-format */ echo _("Military units created 15% faster"); ?></li>
      <li><?php echo _("+5 Monk hit points for each Monastery technology"); ?></li>
      <li><?php echo _("Loom free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Jaguar Warrior"); ?></span>,
   <span class="civ-tech"><?php echo _("Garland Wars(+4 infantry attack)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */ echo _("Relics generate +33% gold"); ?><span>
</div>
<div class="civ-bonus civ-britons">
   <span class="civ-title"><?php echo _("Britons"); ?></span> - 
   <span class="civ-category"><?php echo _("Foot archer civilization"); ?></span>
   <ul>
      <li><?php  /* xgettext:no-php-format */  echo _("Town Centers cost -50% wood upon reaching the Castle Age"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Foot archers (excluding Skirmishers) have +1 range in Castle Age, <br /> +1 in Imperial Age (for +2 total)"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Shepherds work 25% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Longbowman"); ?></span>,
   <span class="civ-tech"><?php echo _("Yeomen (+1 Foot archer range; +2 tower attack)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Archery Ranges work 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-byzantines">
   <span class="civ-title"><?php echo _("Byzantines"); ?></span> - 
   <span class="civ-category"><?php echo _("Defensive civilization"); ?></span>
   <ul>
      <li><?php  /* xgettext:no-php-format */  echo _("Buildings have +10% HP in Dark Age, +20% HP in Feudal Age,<br /> +30% in Castle Age, +40% in Imperial Age."); ?></li>
      <li><?php  /* xgettext:no-php-format */  echo _("Counter units cost 25% less"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Fire Ships attack 20% faster"); ?></li>
      <li><?php  /* xgettext:no-php-format */  echo _("Imperial Age costs -33%"); ?></li>
      <li><?php echo _("Town Watch is free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Cataphract"); ?></span>,
   <span class="civ-tech"><?php echo _("Logistica (Cataphracts cause trample damage)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Monks +50% heal speed"); ?><span>
</div>
<div class="civ-bonus civ-celts">
   <span class="civ-title"><?php echo _("Celts"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry move 15% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Lumberjacks work 15% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Siege weapons fire 20% faster"); ?></li>
      <li><?php echo _("Sheep cannot be stolen if within one Celt unit's line of sight"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Woad Raider"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Furor Celtica (Siege Workshop Units +50% HP)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php  /* xgettext:no-php-format */ echo _("Siege Workshops work 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-chinese">
   <span class="civ-title"><?php echo _("Chinese"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer civilization"); ?></span>
   <ul>
      <li><?php echo _("Start game with 3 extra villagers but -50 Wood and -200 Food"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Technologies cost -10% in Feudal Age, -15% in Castle Age,<br /> -20% in Imperial Age"); ?></li>
      <li><?php echo _("Town Centers support 10 population instead of 5"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Demolition Ships have +50% HP"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Chu Ko Nu"); ?></span>,
   <span class="civ-tech"><?php echo _("Rocketry (+2 Chu Ko Nu pierce attack, +4 scorpions)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Farms provide +45 food"); ?><span>
</div>
<div class="civ-bonus civ-franks">
   <span class="civ-title"><?php echo _("Franks"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Castles are 25% cheaper"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Knights have +20% HP"); ?></li>
      <li><?php echo _("Farm upgrades are free (Mill is required to receive bonus)"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Throwing Axeman"); ?></span>,
   <span class="civ-tech"><?php echo _("Bearded Axe (+1 Throwing Axeman range)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Knights have +2 line of sight"); ?><span>
</div>
<div class="civ-bonus civ-goths">
   <span class="civ-title"><?php echo _("Goths"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php  /* xgettext:no-php-format */ echo _("Infantry cost 33% less (starting in Feudal Age)"); ?></li>
      <li><?php echo _("Infantry have +1 attack against buildings"); ?></li>
      <li><?php echo _("Villagers have +5 attack versus wild boar"); ?></li>
      <li><?php echo _("Hunters carry +15 meat"); ?></li>
      <li><?php echo _("+10 to population limit in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Huskarl"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Anarchy (Create Huskarls at Barracks),Perfusion (Barracks units created 50% faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Barracks operate 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-huns">
   <span class="civ-title"><?php echo _("Huns"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php echo _("Houses are not required to support population, start game with -100 Wood"); ?></li>
      <li><?php echo  /* xgettext:no-php-format */ _("Cavalry Archers cost -25% in Castle Age, -30% in Imperial Age"); ?></li>
      <li><?php echo  /* xgettext:no-php-format */ _("Trebuchets are 35% more accurate"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Tarkan"); ?></span>,
   <span class="civ-tech"><?php  /* xgettext:no-php-format */ echo _("Atheism (+100 years Wonder/Relic victory time; -50% Spies/Treason cost)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php  /* xgettext:no-php-format */ echo _("Stables are 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-japanese">
   <span class="civ-title"><?php echo _("Japanese"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php echo _("Fishing Ships have 2x HP and +2 armor"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Fishing Ships work +5% faster in Dark Age, +10% in Feudal Age, <br />+15% in Castle Age, +20% in Imperial Age"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Lumber Camps, Mining Camps, and Mills are 50% cheaper"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry attack 25% faster (starting in Feudal Age)"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Samurai"); ?></span>,
   <span class="civ-tech"><?php echo _("Kataparuto (Trebuchets fire, pack/unpack faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Galleys have +50% line of sight"); ?><span>
</div>
<div class="civ-bonus civ-koreans">
   <span class="civ-title"><?php echo _("Koreans"); ?></span> - 
   <span class="civ-category"><?php echo _("Tower and naval civilization"); ?></span>
   <ul>
      <li><?php echo _("Villagers have +3 line of sight"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Stone miners work 20% faster"); ?></li>
      <li><?php echo _("Guard Tower and Keep upgrades are free"); ?></li>
      <li><?php echo _("Towers (except bombard towers) have +1 range in Castle Age, +2 in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("War Wagon, Turtle Ship (Dock)"); ?></span>,
   <span class="civ-tech"><?php echo _("Shinkichon (+1 range Mangonels, Onagers)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Mangonel line has +1 range"); ?><span>
</div>
<div class="civ-bonus civ-mayans">
   <span class="civ-title"><?php echo _("Mayans"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer civilization"); ?></span>
   <ul>
      <li><?php echo _("Start with Eagle Warrior"); ?></li>
      <li><?php echo _("Start game with 1 extra villager, -50 Food"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Natural resources last 20% longer"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Archers cost -10% in Feudal Age, -20% in Castle Age, -30% in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Plumed Archer"); ?></span>,
   <span class="civ-tech"><?php echo _("El Dorado (+40 Eagle Warrior HP)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Walls are 50% cheaper"); ?><span>
</div>
<div class="civ-bonus civ-mongols">
   <span class="civ-title"><?php echo _("Mongols"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry Archer civilization"); ?></span>
   <ul>
      <li><?php  /* xgettext:no-php-format */ echo _("Cavalry Archers fire 20% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Light Cavalry and Hussars have +30% HP"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Hunters work 50% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Mangudai"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Drill (Siege Workshop units move 50% faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Scout line has +2 line of sight"); ?><span>
</div>
<div class="civ-bonus civ-persians">
   <span class="civ-title"><?php echo _("Persians"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php echo _("Start game with +50 wood and food"); ?></li>
      <li><?php echo _("Town Center and Docks have 2x HP"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Town Centers and Docks operate +10% faster in Feudal Age, <br /> +15% in Castle Age, +20% in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("War Elephant"); ?></span>,
   <span class="civ-tech"><?php  /* xgettext:no-php-format */ echo _("Mahouts (+30% War Elephant speed)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Knights have +2 attack versus Archers"); ?><span>
</div>
<div class="civ-bonus civ-saracens">
   <span class="civ-title"><?php echo _("Saracens"); ?></span> - 
   <span class="civ-category"><?php echo _("Camel and Naval civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Market trade cost is only 5%"); ?></li>
      <li><?php echo _("Transport Ships have 2x HP and carry capacity"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Galleys attack 20% faster"); ?></li>
      <li><?php echo _("Cavalry Archers have +4 attack against buildings"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Mameluke"); ?></span>,
   <span class="civ-tech"><?php echo _("Zealotry (+30 camel, Mameluke HP)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Foot archers have +2 attack against buildings"); ?><span>
</div>
<div class="civ-bonus civ-spanish">
   <span class="civ-title"><?php echo _("Spanish"); ?></span> - 
   <span class="civ-category"><?php echo _("Gunpowder and Monk civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Villagers construct buildings 30% faster"); ?></li>
      <li><?php echo _("Blacksmith upgrades do not cost any gold"); ?></li>
      <li><?php echo _("Cannon Galleons benefit from Ballistics (improved fire rate and accuracy)"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Hand Cannoneers and Bombard Cannons fire 15% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Conquistador, Missionary"); ?></span>,
   <span class="civ-tech"><?php echo _("Supremacy (Villager combat attributes increased)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Trade units generate +33%"); ?><span>
</div>
<div class="civ-bonus civ-teutons">
   <span class="civ-title"><?php echo _("Teutons"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php echo _("Monks have 2x healing range"); ?></li>
      <li><?php echo _("Towers can garrison 2x units"); ?></li>
      <li><?php echo _("Murder Holes is free"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Farms cost 33% less"); ?></li>
      <li><?php echo _("Town Centers have +2 attack and +5 line of sight"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Teutonic Knight"); ?></span>,
   <span class="civ-tech"><?php echo _("Crenellations (+3 Castle range; garrisoned infantry fire arrows)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Units are more resistant to conversion"); ?><span>
</div>
<div class="civ-bonus civ-turks">
   <span class="civ-title"><?php echo _("Turks"); ?></span> - 
   <span class="civ-category"><?php echo _("Gunpowder civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Gunpowder Units have +25% HP"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Gunpowder technologies cost 50% less"); ?></li>
      <li><?php echo _("Chemistry is free"); ?>/li>
      <li><?php /* xgettext:no-php-format */  echo _("Gold miners work 15% faster"); ?></li>
      <li><?php echo _("Light Cavalry and Hussar upgrades are free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Janissary"); ?></span>,
   <span class="civ-tech"><?php echo _("Artillery (+2 range Bombard Towers, Bombard Cannons, Cannon Galleons)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Gunpowder units are created 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-vikings">
   <span class="civ-title"><?php echo _("Vikings"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry and naval civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Warships cost 25% less"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry have +10% HP in Feudal Age, +15% in Castle Age, <br /> +20% in Imperial Age"); ?></li>
      <li><?php echo _("Wheelbarrow and Hand Cart are free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Berserker, Longboat (Dock)"); ?></span>,
   <span class="civ-tech"><?php echo _("Berserkergang (Berserks regenerate faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Docks are 25% cheaper"); ?><span>
</div>
</div>
<?php
    }

    public function setup_dlc_tooltips_data()
    {
?>
<div id="#civ-bonuses" style="display: none">
<div class="civ-bonus civ-random">
   <span class="civ-title"><?php echo _("Unknown"); ?></span>
   <span><br /><?php echo _("Different meanings based on the situation."); ?></span>
   <ul>
      <li><?php echo _("Random civ - when you see this as badge, <br />it means one of the captains didn't pick in time and a random civilization was picked for him."); ?></li>
      <li><?php echo _("No ban - when you see this as banned, <br />it means no civilization got banned"); ?></li>
   </ul>

</div>
<div class="civ-bonus civ-aztecs">
   <span class="civ-title"><img src="images/aocversion/aoc_16.png" />&nbsp;<?php echo _("Aztecs"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry and Monk civilization"); ?></span>
   <ul>
      <li><?php echo _("Villagers carry +5"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Military units created 15% faster"); ?></li>
      <li><?php echo _("+5 Monk hit points for each Monastery technology"); ?></li>
      <li><?php echo _("Start with +50 gold"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Jaguar Warrior"); ?></span>,
   <span class="civ-tech"><?php echo _("Atlatl(skirmishers +1 range, +1 pierce attack),Garland Wars(+4 infantry attack)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Relics generate +33% gold"); ?><span>
</div>
<div class="civ-bonus civ-berbers">
   <span class="civ-title"><img src="images/aocversion/aoa_16.png" />&nbsp;<?php echo _("Berbers"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry and Naval civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Villagers move 10% faster"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Ships move 10% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Stable units cost -15% in Castle and -20% in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Camel Archers, Genitour"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Kasbah(castles work 25% faster), Maghrabi Camel(camel troops regenerate)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Genitour available in archery range"); ?><span>
</div>
<div class="civ-bonus civ-britons">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Britons"); ?></span> - 
   <span class="civ-category"><?php echo _("Foot archer civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Town Centers cost -50% wood upon reaching the Castle Age"); ?></li>
      <li><?php echo _("Foot archers (excluding Skirmishers) have +1 range in Castle Age, <br /> +1 in Imperial Age (for +2 total)"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Shepherds work 25% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Longbowman"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Yeomen (+1 Foot archer range; +2 tower attack), Warwolf(Trebuchets 100% accuracy +0.5 blast radius)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Archery Ranges work 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-burmese">
   <span class="civ-title"><img src="images/aocversion/aor_16.png" />&nbsp;<?php echo _("Burmese"); ?></span> - 
   <span class="civ-category"><?php echo _("Monk and Elephant civilization"); ?></span>
   <ul>
      <li><?php echo _("Free Lumbercamp upgrades"); ?></li>
      <li><?php echo _("Infantry +1 attack per Age"); ?></li>
      <li><?php  /* xgettext:no-php-format */ echo _("Monastery techs 50% cheaper"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Arambai"); ?></span>,
   <span class="civ-tech"><?php echo _("Howdah (Battle Elephants +1/+2 armor); Manipur Cavalry (Cavalry and Arambai +6 attack vs buildings)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Relics visible on map"); ?><span>
</div>
<div class="civ-bonus civ-byzantines">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Byzantines"); ?></span> - 
   <span class="civ-category"><?php echo _("Defensive civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Buildings have +10% HP in Dark Age, +20% HP in Feudal Age,<br /> +30% in Castle Age, +40% in Imperial Age."); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Counter units cost 25% less"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Fire Ships attack 20% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Imperial Age costs -33%"); ?></li>
      <li><?php echo _("Town Watch is free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Cataphract"); ?></span>,
   <span class="civ-tech"><?php echo _("Greek Fire (Fire ships +1 range),Logistica (Cataphracts cause trample damage)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Monks +50% heal speed"); ?><span>
</div>
<div class="civ-bonus civ-celts">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Celts"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry move 15% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Lumberjacks work 15% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Siege weapons fire 20% faster"); ?></li>
      <li><?php echo _("Sheep cannot be stolen if within one Celt unit's line of sight"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Woad Raider"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Stronghold (Castles, towers -20% reload time), Furor Celtica (Siege Workshop Units +50% HP)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Siege Workshops work 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-chinese">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Chinese"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer civilization"); ?></span>
   <ul>
      <li><?php echo _("Start game with 3 extra villagers but -50 Wood and -200 Food"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Technologies cost -10% in Feudal Age, -15% in Castle Age,<br /> -20% in Imperial Age"); ?></li>
      <li><?php echo _("Town Centers support 10 population instead of 5"); ?></li>
      <li><?php echo _("Town Centers +5 LOS"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Demolition Ships have +50% HP"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Chu Ko Nu"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Great Wall (Walls, gates, towers +30% HP), Rocketry (+2 Chu Ko Nu pierce attack, +4 scorpions)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Farms provide +45 food"); ?><span>
</div>
<div class="civ-bonus civ-ethiopians">
   <span class="civ-title"><img src="images/aocversion/aoa_16.png" />&nbsp;<?php echo _("Ethiopians"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer civilization"); ?></span>
   <ul>
      <li><?php echo _("Receive 100F 100G upon advancing to next age"); ?></li>
      <li><?php echo _("Pikeman and Halberdier upgrades are free"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Foot archers 15% faster reload"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Shotel Warrior"); ?></span>,
   <span class="civ-tech"><?php echo _("Royal Heirs (Shotel warrior created 2x faster), Torsion Engines (+0.45 blast radius for siege workshop units)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Towers, outposts +3 LOS"); ?><span>
</div>
<div class="civ-bonus civ-franks">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Franks"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Foragers work 25% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Castles are 25% cheaper"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Knights have +20% HP"); ?></li>
      <li><?php echo _("Farm upgrades are free (Mill is required to receive bonus)"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Throwing Axeman"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Chivalry (stables work 40% faster), Bearded Axe (+1 Throwing Axeman range)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Knights have +2 line of sight"); ?><span>
</div>
<div class="civ-bonus civ-goths">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Goths"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry cost 35% less (starting in Feudal Age)"); ?></li>
      <li><?php echo _("Infantry have +1 attack against buildings"); ?></li>
      <li><?php echo _("Villagers have +5 attack versus wild boar"); ?></li>
      <li><?php echo _("Hunters carry +15 meat"); ?></li>
      <li><?php echo _("+10 to population limit in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Huskarl"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Anarchy (Create Huskarls at Barracks),Perfusion (Barracks units created 50% faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Barracks operate 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-huns">
   <span class="civ-title"><img src="images/aocversion/aoc_16.png" />&nbsp;<?php echo _("Huns"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php echo _("Houses are not required to support population, start game with -100 Wood"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Cavalry Archers cost -10% in Castle Age, -20% in Imperial Age"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Trebuchets are 35% more accurate"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Tarkan"); ?></span>,
   <span class="civ-tech"><?php echo _("Marauders (Tarkans available in stables), Atheism (+100 years Wonder/Relic victory time; -50% Spies/Treason cost)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Stables are 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-incas">
   <span class="civ-title"><img src="images/aocversion/aof_16.png" />&nbsp;<?php echo _("Incas"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php echo _("Start with free llama"); ?></li>
      <li><?php echo _("Villagers affected by blacksmith upgrades"); ?></li>
      <li><?php echo _("Houses support 10 population"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Buildings cost -15% stone"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Kamayuk, Slinger (Archery Range)"); ?></span>,
   <span class="civ-tech"><?php echo _("Andean Sling (skirmishers, slinger no minimum range), Couriers (eagles, kamayuks, slingers +1/+2 armor)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Farms built 50% faster"); ?><span>
</div>
<div class="civ-bonus civ-indians">
   <span class="civ-title"><img src="images/aocversion/aof_16.png" />&nbsp;<?php echo _("Indians"); ?></span> - 
   <span class="civ-category"><?php echo _("Camel and Gunpowder civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Villagers Cost -10% in Dark, -15% in Feudal, -20% in Castle, -25% in Imperial"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Fishermen(villagers) work 15% faster and carry +15"); ?></li>
      <li><?php echo _("Camels +1/+1 armor"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Elephant Archer, Imperial Camel (Stable)"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Sultans (gold miners +10% faster, trade units +10% gold, relics +5G/min), Shatagni (hand cannoneers +1 range)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Camels +6, Camel Archers +5  attack vs buildings"); ?><span>
</div>
<div class="civ-bonus civ-italians">
   <span class="civ-title"><img src="images/aocversion/aof_16.png" />&nbsp;<?php echo _("Italians"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer and Naval civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Advancing to the next age costs -15%"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Dock techs cost -50%"); ?></li>
      <li><?php echo _("Fishing ships cost -25W"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Gunpowder units cost -20%"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Genoese crossbowman, Condottiero(Barracks)"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Pavise (foot archers +1/+1 armor), Silk road (trade units cost -50%)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Condottiero available in imperial barracks"); ?><span>
</div>
<div class="civ-bonus civ-japanese">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Japanese"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php echo _("Fishing Ships have 2x HP and +2 armor"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Fishing Ships work +5% faster in Dark Age, +10% in Feudal Age, <br />+15% in Castle Age, +20% in Imperial Age"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Lumber Camps, Mining Camps, and Mills are 50% cheaper"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry attack 25% faster (starting in Feudal Age)"); ?>.</li>
   </ul>
   <span class="civ-unique"><?php echo _("Samurai"); ?></span>,
   <span class="civ-tech"><?php echo _("Yasama (towers shoot +3 arrows), Kataparuto (Trebuchets fire, pack/unpack faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Galleys have +50% line of sight"); ?><span>
</div>
<div class="civ-bonus civ-khmer">
   <span class="civ-title"><img src="images/aocversion/aor_16.png" />&nbsp;<?php echo _("Khmer"); ?></span> - 
   <span class="civ-category"><?php echo _("Siege and Elephant civilization"); ?></span>
   <ul>
      <li><?php echo _("No buildings required to advance to the next Age or to unlock other buildings"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Battle Elephants +15% faster"); ?></li>
      <li><?php echo _("Villagers can garrison in Houses"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Ballista Elephant"); ?></span>,
   <span class="civ-tech"><?php echo _("Tusk Swords (Battle Elephants +3 attack), Double crossbow (Ballista Elephants and Scorpions shoot two projectiles)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Scorpions +1 range"); ?><span>
</div>
<div class="civ-bonus civ-koreans">
   <span class="civ-title"><img src="images/aocversion/aoc_16.png" />&nbsp;<?php echo _("Koreans"); ?></span> - 
   <span class="civ-category"><?php echo _("Tower and naval civilization"); ?></span>
   <ul>
      <li><?php echo _("Villagers have +3 line of sight"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Stone miners work 20% faster"); ?></li>
      <li><?php echo _("Guard Tower and Keep upgrades are free"); ?></li>
      <li><?php echo _("Towers (except bombard towers) have +1 range in Castle Age, +2 in Imperial Age"); ?></li>
      <li><?php /* xgettext:no-php-format */ echo _("Fortifications (walls except gates, castles, towers) are built 25% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("War Wagon, Turtle Ship (Dock)"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Panokseon (turtle ships move 15% faster), Shinkichon (+1 range Mangonels, Onagers)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Mangonel minimum range 1 (instead of 3)"); ?><span>
</div>
<div class="civ-bonus civ-magyars">
   <span class="civ-title"><img src="images/aocversion/aof_16.png" />&nbsp;<?php echo _("Magyars"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php echo _("Villagers kill wolves in 1 strike"); ?></li>
      <li><?php echo _("Melee upgrades (forging, iron casting, blast furnace) in blacksmith free (requires blacksmith)"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Scout line costs -10%"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Magyar Huszar"); ?></span>,
   <span class="civ-tech"><?php echo _("Mercenaries (Magyar Huszar costs no gold), Recurve bow (Cavalry archers +1 range)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Foot archers (not skirmishers) +2 LOS"); ?><span>
</div>
<div class="civ-bonus civ-malay">
   <span class="civ-title"><img src="images/aocversion/aor_16.png" />&nbsp;<?php echo _("Malay"); ?></span> - 
   <span class="civ-category"><?php echo _("Naval civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Advancing to Ages +100% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */ echo _("Fishing Ships and Fish Traps cost -33%"); ?></li>
      <li><?php echo _("Fish Traps provide unlimited food"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Battle Elephants 20% cheaper"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Karambit Warrior"); ?></span>,
   <span class="civ-tech"><?php echo _("Thalassocracy (Docks upgraded to Harbors, which shoot arrows), Forced Levy (Militia-line costs no gold)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */ echo _("Docks +100% LOS"); ?><span>
</div>
<div class="civ-bonus civ-malians">
   <span class="civ-title"><img src="images/aocversion/aoa_16.png" />&nbsp;<?php echo _("Malians"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php echo _("Barracks units get +1 pierce armor per age"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Buildings (except farms) wood cost -15%"); ?></li>
      <li><?php echo _("Gold mining upgrades free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Gbeto"); ?></span>,
   <span class="civ-tech"><?php echo _("Tigui (TC fire arrows without garrisoned villagers), Farimba (Cavalry, camels +5 attack)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("University operates 80% faster"); ?><span>
</div>
<div class="civ-bonus civ-mayans">
   <span class="civ-title"><img src="images/aocversion/aoc_16.png" />&nbsp;<?php echo _("Mayans"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer civilization"); ?></span>
   <ul>
      <li><?php echo _("Start with Eagle Warrior"); ?></li>
      <li><?php echo _("Start game with 1 extra villager, -50 Food"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Natural resources last 15% longer"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Archers cost -10% in Feudal Age, -20% in Castle Age, -30% in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Plumed Archer"); ?></span>,
   <span class="civ-tech"><?php echo _("Obsidian Arrow (archer line +6 attack vs buildings), El Dorado (+40 Eagle Warrior HP)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Walls are 50% cheaper"); ?><span>
</div>
<div class="civ-bonus civ-mongols">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Mongols"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry Archer civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Cavalry Archers fire 20% faster"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Light Cavalry and Hussars have +30% HP"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Hunters work 50% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Mangudai"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */ echo _("Nomads (destroyed houses don't loose population room), Drill (Siege Workshop units move 50% faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Scout line has +2 line of sight"); ?><span>
</div>
<div class="civ-bonus civ-persians">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Persians"); ?></span> - 
   <span class="civ-category"><?php echo _("Cavalry civilization"); ?></span>
   <ul>
      <li><?php echo _("Start game with +50 wood and food"); ?></li>
      <li><?php echo _("Town Center and Docks have 2x HP"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Town Centers and Docks operate +10% faster in Feudal Age, <br /> +15% in Castle Age, +20% in Imperial Age"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("War Elephant"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Boiling Oil (Castles +9 attack vs rams), Mahouts (+30% War Elephant speed)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Knights have +2 attack versus Archers"); ?><span>
</div>
<div class="civ-bonus civ-portuguese">
   <span class="civ-title"><img src="images/aocversion/aoa_16.png" />&nbsp;<?php echo _("Portuguese"); ?></span> - 
   <span class="civ-category"><?php echo _("Naval and Gunpowder civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("All units cost -15% gold"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("All ships +10% HP"); ?></li>
      <li><?php echo _("Get Feitoria"); ?><br />
   </ul>
   <span class="civ-unique"><?php echo _("Organ Gun, Caravel (dock)"); ?></span>,
   <span class="civ-tech"><?php echo _("Carrack (ships +1/+1 armor), Arquebus (gunpowder units affected by ballistics)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Free cartography from Dark Age"); ?><span>
</div>
<div class="civ-bonus civ-saracens">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Saracens"); ?></span> - 
   <span class="civ-category"><?php echo _("Camel and Naval civilization"); ?></span>
   <ul>
      <li><?php echo _("Market costs -75 wood"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Market trade cost is only 5%"); ?></li>
      <li><?php echo _("Transport Ships have 2x HP and carry capacity"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Galleys attack 20% faster"); ?></li>
      <li><?php echo _("Cavalry Archers have +4 attack against buildings"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Mameluke"); ?></span>,
   <span class="civ-tech"><?php /* xgettext:no-php-format */  echo _("Madrasah (monks return 33% of their cost), Zealotry (+30 camel, Mameluke HP)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Foot archers have +2 attack against buildings"); ?><span>
</div>
<div class="civ-bonus civ-slavs">
   <span class="civ-title"><img src="images/aocversion/aof_16.png" />&nbsp;<?php echo _("Slavs"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry and siege civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Farmers work ~15% faster without upgrades"); ?></li>
      <li><?php echo _("Tracking free"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Siege units from siege workshop 15% cheaper"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Boyar"); ?></span>,
   <span class="civ-tech"><?php echo _("Orthodoxy (monks +3/+3 armor), Druzhina (infantry do 5 damage to adjecent enemy units)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Military buildings provide +5 population"); ?><span>
</div>
<div class="civ-bonus civ-spanish">
   <span class="civ-title"><img src="images/aocversion/aoc_16.png" />&nbsp;<?php echo _("Spanish"); ?></span> - 
   <span class="civ-category"><?php echo _("Gunpowder and Monk civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Villagers construct buildings 30% faster"); ?></li>
      <li><?php echo _("Blacksmith upgrades do not cost any gold"); ?></li>
      <li><?php echo _("Cannon Galleons benefit from Ballistics (improved fire rate and accuracy)"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Hand Cannoneers and Bombard Cannons fire 15% faster"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Conquistador, Missionary"); ?></span>,
   <span class="civ-tech"><?php echo _("Inquisition (monks convert faster), Supremacy (Villager combat attributes increased)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Trade units generate +25%"); ?><span>
</div>
<div class="civ-bonus civ-teutons">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Teutons"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry civilization"); ?></span>
   <ul>
      <li><?php echo _("Monks have 2x healing range"); ?></li>
      <li><?php echo _("Towers can garrison 2x units"); ?></li>
      <li><?php echo _("Murder Holes is free"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Farms cost 33% less"); ?></li>
      <li><?php echo _("Town Centers garrison +10 units, shoot +5 arrows"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Teutonic Knight"); ?></span>,
   <span class="civ-tech"><?php echo _("Ironclad (siege weapons +4/+0 armor), Crenellations (+3 Castle range; garrisoned infantry fire arrows)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Units are more resistant to conversion"); ?><span>
</div>
<div class="civ-bonus civ-turks">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Turks"); ?></span> - 
   <span class="civ-category"><?php echo _("Gunpowder civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Gunpowder Units have +25% HP"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Gunpowder technologies cost 50% less"); ?></li>
      <li><?php echo _("Chemistry is free"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Gold miners work 20% faster"); ?></li>
      <li><?php echo _("Light Cavalry and Hussar upgrades are free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Janissary"); ?></span>,
   <span class="civ-tech"><?php echo _("Sipahi (Cavalry archers +20HP), Artillery (+2 range Bombard Towers, Bombard Cannons, Cannon Galleons)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php /* xgettext:no-php-format */  echo _("Gunpowder units are created 20% faster"); ?><span>
</div>
<div class="civ-bonus civ-vietnamese">
   <span class="civ-title"><img src="images/aocversion/aor_16.png" />&nbsp;<?php echo _("Vietnamese"); ?></span> - 
   <span class="civ-category"><?php echo _("Archer civilization"); ?></span>
   <ul>
      <li><?php echo _("Reveal enemy positions at game start"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Archery Range units +10% HP Feudal, +15% Castle, +20% Imperial Age"); ?></li>
      <li><?php echo _("Free Conscription"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Rattan Archer"); ?></span>,
   <span class="civ-tech"><?php echo _("Chatras (Battle Elephants +30 HP, Paper Money (Tributes 500 gold to each Ally)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php echo _("Have access to Imperial Skirmisher upgrade"); ?><span>
</div>
<div class="civ-bonus civ-vikings">
   <span class="civ-title"><img src="images/aocversion/aok_16.png" />&nbsp;<?php echo _("Vikings"); ?></span> - 
   <span class="civ-category"><?php echo _("Infantry and naval civilization"); ?></span>
   <ul>
      <li><?php /* xgettext:no-php-format */  echo _("Warships cost -10% in Feudal, -15% in Castle, -20% in Imperial Age"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Infantry have +10% HP in Feudal Age, +15% in Castle Age, <br /> +20% in Imperial Age"); ?></li>
      <li><?php /* xgettext:no-php-format */  echo _("Wheelbarrow and Hand Cart are free"); ?></li>
   </ul>
   <span class="civ-unique"><?php echo _("Berserker, Longboat (Dock)"); ?></span>,
   <span class="civ-tech"><?php echo _("Chieftains (Berserks +5 attack vs cavalry), Berserkergang (Berserks regenerate faster)"); ?></span><br />
   <br />
   <span class="team-bonus"><?php  /* xgettext:no-php-format */ echo _("Docks cost -15%"); ?><span>
</div>
</div>
<?php
    }

    public function get_civs()
    {
        $civs = self::$aoc_civs;
        if($this->aoe_version >= Draft::AOE_VERSION_AOF) {
            $civs = array_merge($civs, self::$aof_civs);
        }
        if($this->aoe_version >= Draft::AOE_VERSION_AOAK) {
            $civs = array_merge($civs, self::$aoak_civs);
        }
        if($this->aoe_version >= Draft::AOE_VERSION_AOR) {
            $civs = array_merge($civs, self::$aor_civs);
        }

        sort($civs);

        array_unshift($civs, "random");
        return $civs;
    }

    public function display_grid()
    {
        $civs = $this->get_civs();
?>
   <div id="civgrid" class="chooser card">
      <div class="pure-g chooser-grid box-content">
<?php
        foreach( $civs as $civ ) {
            $civ_img = $civ.".png";
            if($this->aoe_version < Draft::AOE_VERSION_AOF) {
                $civ_img = $civ."_orig.png";
            }
?>
            <div class="pure-u-1-12 choice" data-civ="<?php echo $civ; ?>">
               <div class='stretchy-wrapper'>
                  <div class='stretchy-image'>
                  <img src="images/civs/<?php echo $civ_img; ?>"/>
                  </div>
                  <div class='stretchy-text'>
                     <?php echo _(ucfirst($civ)); ?>
                  </div>
               </div>
            </div>
<?php
        }
?>
         <div class="pure-u-1-12 choice" style="display:none">
               <div class='stretchy-wrapper chosen-hidden'>
                  <div class='stretchy-image'>
                  <img src="images/civs/hidden.png"/>
                  </div>
               </div>
            </div>
      </div>
      <div class="card-background"></div>
   </div>
<?php
    }
}
