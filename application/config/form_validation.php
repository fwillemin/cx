<?php

$config = array(
    /* Connexion */
    'identification' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pass',
            'label' => 'Mot de passe',
            'rules' => 'required|trim'
        )
    ),
    /* Pdv */
    'majPdv' => array(
        array(
            'field' => 'modPdvNomCommercial',
            'label' => 'Nom commercal',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'modPdvSiren',
            'label' => 'Siren',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'modPdvTvaIntracom',
            'label' => 'Numéro de TVA',
            'rules' => 'trim'
        ),
        array(
            'field' => 'modPdvApe',
            'label' => 'APE/NAF',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'modPdvAdresse1',
            'label' => 'Adresse',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'modPdvAdresse2',
            'label' => 'Complément d\'adresse',
            'rules' => 'trim'
        ),
        array(
            'field' => 'modPdvCp',
            'label' => 'Code postal',
            'rules' => 'required|trim|numeric'
        ),
        array(
            'field' => 'modPdvVille',
            'label' => 'Ville',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'modPdvFax',
            'label' => 'Fax',
            'rules' => 'trim'
        ),
        array(
            'field' => 'modPdvWww',
            'label' => 'Site internet',
            'rules' => 'trim'
        ),
        array(
            'field' => 'modPdvTelephone',
            'label' => 'Téléphone',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'modPdvEmail',
            'label' => 'Email',
            'rules' => 'required|trim|valid_email'
        ),
        array(
            'field' => 'modPdvTelephoneCommercial',
            'label' => 'Téléphone commercial',
            'rules' => 'trim'
        ),
        array(
            'field' => 'modPdvEmailCommercial',
            'label' => 'Email',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'modPdvTelephoneTechnique',
            'label' => 'Téléphone technique',
            'rules' => 'trim'
        ),
        array(
            'field' => 'modPdvEmailTechnique',
            'label' => 'Email technique',
            'rules' => 'trim|valid_email'
        )
    ),
    /* getDevis */
    'getDevis' => array(
        array(
            'field' => 'devisId',
            'label' => 'ID du devis',
            'rules' => 'required|callback_existDevis'
        )
    ),
    /* devisPerdu */
    'devisPerdu' => array(
        array(
            'field' => 'devisId',
            'label' => 'ID du devis',
            'rules' => 'required|callback_existDevis'
        ),
        array(
            'field' => 'motif',
            'label' => 'Motif',
            'rules' => 'required|in_list[2,3,4]'
        )
    ),
    /* getBdc */
    'getBdc' => array(
        array(
            'field' => 'bdcId',
            'label' => 'ID du bdc',
            'rules' => 'required|callback_existBdc'
        )
    ),
    /* getBl */
    'getBl' => array(
        array(
            'field' => 'blId',
            'label' => 'ID du bl',
            'rules' => 'required|callback_existBl'
        )
    ),
    /* getFacture */
    'getFacture' => array(
        array(
            'field' => 'factureId',
            'label' => 'ID de la facture',
            'rules' => 'required|callback_existFacture'
        )
    ),
    /* AddCartArticle */
    'addCartArticle' => array(
        array(
            'field' => 'addArticleRowid',
            'label' => 'RowId',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addArticleProduitId',
            'label' => 'ID produit',
            'rules' => 'trim|callback_existProduit'
        ),
        array(
            'field' => 'addArticleId',
            'label' => 'ID article',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'addArticleDesignation',
            'label' => 'Désignation',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addArticlePrixUnitaire',
            'label' => 'Prix unitaire',
            'rules' => 'trim|numeric|required'
        ),
        array(
            'field' => 'addArticleTauxTVA',
            'label' => 'Taux YVA',
            'rules' => 'trim|required|numeric'
        ),
        array(
            'field' => 'addArticleQte',
            'label' => 'Quantité',
            'rules' => 'trim|numeric|required'
        ),
        array(
            'field' => 'addArticleRemise',
            'label' => 'Remise',
            'rules' => 'numeric|required|trim|greater_than_equal_to[0]|less_than_equal_to[100]'
        ),
        array(
            'field' => 'addArticleUniteId',
            'label' => 'Unité',
            'rules' => 'in_list[1,2,3]|required|trim'
        )
    ),
    /* getCartArticle */
    'getRowArticle' => array(
        array(
            'field' => 'rowid',
            'label' => 'RowId',
            'rules' => 'trim|required'
        )
    ),
    /* addReglement */
    'addReglement' => array(
        array(
            'field' => 'addReglementId',
            'label' => 'ID du réglement',
            'rules' => 'trim|callback_existReglement'
        ),
        array(
            'field' => 'addReglementMode',
            'label' => 'Mode de réglement',
            'rules' => 'trim|required|in_list[1,2,3,4,5]'
        ),
        array(
            'field' => 'addReglementMontant',
            'label' => 'Montant',
            'rules' => 'trim|required|greater_than_equal_to[0]'
        ),
        array(
            'field' => 'addReglementObjet',
            'label' => 'Objet du réglement',
            'rules' => 'trim|required|is_natural'
        )
    ),
    /* affecte un réglement à une facture */
    'affecteReglement' => array(
        array(
            'field' => 'reglementId',
            'label' => 'ID du réglement',
            'rules' => 'trim|required|callback_existReglement'
        ),
        array(
            'field' => 'factureId',
            'label' => 'ID de la facture',
            'rules' => 'trim|callback_existFacture'
        )
    ),
    'modAvoirQte' => array(
        array(
            'field' => 'rowId',
            'label' => 'Row Id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'qte',
            'label' => 'Qte',
            'rules' => 'required|numeric|greater_than_equal_to[0]'
        )
    ),
    'modAvoirName' => array(
        array(
            'field' => 'rowId',
            'label' => 'Row Id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'name',
            'label' => 'Description',
            'rules' => 'required'
        )
    ),
    'modAvoirPrix' => array(
        array(
            'field' => 'rowId',
            'label' => 'Row Id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'prix',
            'label' => 'Prix',
            'rules' => 'required|numeric|greater_than_equal_to[0]'
        )
    )
);
?>