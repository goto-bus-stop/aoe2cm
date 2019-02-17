<?php
$version = json_decode(file_get_contents(__DIR__.'/../composer.json'))->version;
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/pure/pure-min.css">
<?php // phpcs:disable ?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<?php // phpcs:enable ?>
<link rel="stylesheet" type="text/css" href="tooltipster/css/tooltipster.css" />
<link rel="stylesheet" type="text/css" href="css/chosen.css">
<link rel="stylesheet" type="text/css" href="css/chosenImage.css">
<link rel="stylesheet" type="text/css" href="css/style.css?<?php echo $version;?>" media="screen">
<link rel="stylesheet" type="text/css" href="css/style-material.css?<?php echo $version;?>" media="screen">
<?php if ($this->theme == 'material-chroma') { ?>
    <link rel="stylesheet" type="text/css" href="css/style-material-chroma.css?<?php echo $version;?>" media="screen">
<?php } elseif ($this->theme == 'material-dark') { ?>
    <link rel="stylesheet" type="text/css" href="css/style-material-dark.css?<?php echo $version;?>" media="screen">
<?php } ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
<script src="js/js.cookie.js"></script>
<script src="js/jquery-2.1.4.js"></script>
<script src="js/socket.io.js"></script>
<script type="text/javascript" src="tooltipster/js/jquery.tooltipster.min.js"></script>
<script src="js/app.js?<?php echo $version;?>"></script>
<script src="js/util.js?<?php echo $version;?>"></script>
<script src="js/admin-util.js?<?php echo $version;?>"></script>
<script src="js/jquery.countdown.js"></script>
<script src="js/jquery.jeditable.mini.js"></script>
<!--script src="js/chosen.proto.min.js"></script-->
<script src="js/chosen.jquery.min.js"></script>
<script src="js/chosenImage.jquery.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<link rel="icon" 
      type="image/png" 
      href="<?php echo ROOTDIR; ?>/captainsmodefav.png">

<script>
//cookie warning
window.addEventListener("load", function () {
    window.cookieconsent.initialise({
        "palette": {
            "popup": { "background": "#252e39" },
            "button": { "background": "#009688" }
        },
        "theme": "classic",
        "position": "top",
        "content": {
            <?php // phpcs:disable ?>
            "message": "This website uses cookies to ensure you get the best experience on our website. Without cookies you are limited to spectating; hosting and joining will not work properly!"
            <?php // phpcs:enable ?>
        }
    });
});
//check theme
if (typeof Cookies.get("version") === "undefined" || Cookies.get("version") != '1.3.2') {
    Cookies.set("version", '1.3.2', {expires: 14});
    document.location.reload(true);
}
</script>
</head>
<body>
<div class="wrapper" id="wrapper">
<?php if (isset($goUp) && $goUp == true) { ?>
    <div style="position: absolute; top: 8px; left: 8px;">
        <span><a href='<?php echo ROOTDIR; ?>/'>
            <span class='back-icon header-navigation'><?php echo _("back"); ?></span>
        </a></span>
    </div>
<?php } ?>
