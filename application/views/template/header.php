<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?= (!empty($title)) ? $title : false; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?= (!empty($description)) ? $description : false; ?>">
        <meta name="keywords" content="<?= (!empty($keywords)) ? $keywords : false; ?>">
        <meta name="author" content="Xanthellis - Créateur d'applications professionnelles">

        <!-- Le styles -->
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png'); ?>" >
        <link rel="stylesheet" media="screen" href="<?= base_url('assets/bootstrap-3.3.7/css/bootstrap.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap-table/dist/bootstrap-table.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/jqueryConfirm/jquery-confirm.min.css'); ?>" >

        <link href="<?= base_url('assets/css/cx.css'); ?>" rel="stylesheet">

        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbar/MegaNavbar.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbar/navbar-violet-light.css'); ?>">


        <!--Start of Zopim Live Chat Script-->
        <script type="text/javascript">
//            window.$zopim || (function (d, s) {
//                var z = $zopim = function (c) {
//                    z._.push(c)
//                }, $ = z.s =
//                        d.createElement(s), e = d.getElementsByTagName(s)[0];
//                z.set = function (o) {
//                    z.set.
//                            _.push(o)
//                };
//                z._ = [];
//                z.set._ = [];
//                $.async = !0;
//                $.setAttribute("charset", "utf-8");
//                $.src = "//v2.zopim.com/?2yZAUEh9TKmKurdGhmu6rBHEtDVWinAE";
//                z.t = +new Date;
//                $.
//                        type = "text/javascript";
//                e.parentNode.insertBefore($, e)
//            })(document, "script");
        </script>
        <!--End of Zopim Live Chat Script-->

        <!-- reload grunt --><script src="//localhost:35729/livereload.js"></script>
    </head>

    <body>

        <?php if ($this->ion_auth->logged_in()): ?>
            <div class="container-fluid">

                <div style="position:absolute; top:52px; right:20px;">
                    <label class="label label-default">Version 2.0.3</label>
                </div>

                <nav class="navbar navbar-violet-light" role="navigation" id="navHeader">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar_id">
                            <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                        </button>

                    </div>
                    <div class="collapse navbar-collapse" id="navbar_id">
                        <ul class="nav navbar-nav navbar-left">
                            <li class="dropdown-short" style="padding:0px; margin: 0px;">
                                <a href="<?= site_url(); ?>" class=""><img src="<?= base_url('assets/img/cx.png'); ?>" style="max-height: 20px;" ></a>
                            </li>
                            <li class="dropdown-grid">
                                <a href="<?= site_url('clients/liste'); ?>" class=""><i class="fas fa-user-circle"></i> Clients</a>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fas fa-file"></i> Devis<span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('chiffrages/resetDevisEncours'); ?>"><i class="fas fa-asterisk"></i> Nouveau</a></li>
                                    <li><a href="<?= site_url('chiffrages/devisListe'); ?>"><i class="fas fa-list-alt"></i> Liste</a></li>
                                    <li class="divider"></li>
                                    <li class="disabled"><a href="#"><i class="fas fa-cog fa-spin"></i> Analyse</a></li>
                                </ul>
                            </li>

                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="far fa-file"></i> Bons de commande<span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('ventes/bdcListe'); ?>"><i class="fas fa-list-alt"></i> liste</a></li>
                                </ul>
                            </li>

                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fas fa-book"></i> Catalogue<span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('produits/'); ?>"><i class="fab fa-product-hunt"></i> Produits</a></li>
                                    <li class="disabled"><a href="#"><i class="fas fa-square"></i> Approvisionnements</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-short">
                                <a href="javascript:;" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle"><i class="fas fa-university"></i> Administration<span class="fa fa-caret-down" style="margin-left: 5px;"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= site_url('facturation/blNonFactures'); ?>"><i class="fas fa-fire"></i> Livraisons non facturées</a></li>
    <!--                                    <li><a href="<?= site_url('factures'); ?>"><i class="fas fa-fire"></i> Factures</a></li>
                                    <li><a href="<?= site_url('factures/avoirs'); ?>"><i class="fas fa-bank"></i> Avoirs</a></li>-->
                                    <li><a href="<?= site_url('facturation/encaissements'); ?>"><i class="fas fa-chart-area"></i> CA et Encaissements</a></li>
                                    <li><a href="<?= site_url('facturation/caParClient'); ?>"><i class="fas fa-chart-area"></i> CA / clients</a></li>
    <!--                                    <li><a href="<?= site_url('facturation/caisse'); ?>"><i class="fas fa-"></i> Caisse</a></li>
                                    <li><a href="<?= site_url('facturation/remise'); ?>"><i class="fas fa-"></i> Remises de chèques</a></li>
                                    <li><a href="<?= site_url('clotures/liste'); ?>"><i class="fas fa-lock"></i> Clôtures de caisse</a></li>-->
                                </ul>
                            </li>
                            <li class="dropdown-grid">
                                <a href="<?= site_url('settings'); ?>" class=""><i class="fas fa-cog"></i></a>
                            </li>
                            <li class="dropdown-grid">
                                <a href="<?= site_url('cx/deconnexion'); ?>" class=""><i class="fas fa-window-close" style="color: red;"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        <?php endif;
        ?>
