<?php

// Usage : [$module, $action] = explode('.', $permission->name);
//         __('permissions.actions.'.$action).' – '.__('permissions.modules.'.$module)

return [
    'acces-admin' => "Accès à l'espace d'administration",

    'roles' => [
        'admin'        => 'Administrateur',
        'gestionnaire' => 'Gestionnaire',
        'caissiere'    => 'Caissière',
        'fournisseur'  => 'Fournisseur',
        'client'       => 'Client',
    ],

    'actions' => [
        'voir'         => 'Voir',
        'creer'        => 'Créer',
        'modifier'     => 'Modifier',
        'supprimer'    => 'Supprimer',
        'receptionner' => 'Réceptionner',
        'annuler'      => 'Annuler',
        'valider'      => 'Valider',
        'ajuster'      => 'Ajuster',
    ],

    'modules' => [
        'dashboard'          => 'Tableau de bord',
        'categories'         => 'Catégories',
        'sous-categories'    => 'Sous-catégories',
        'produits'           => 'Produits',
        'zones'              => 'Zones de livraison',
        'users'              => 'Utilisateurs',
        'livreurs'           => 'Livreurs',
        'fournisseurs'       => 'Fournisseurs',
        'depenses'           => 'Dépenses',
        'categorie-depenses' => 'Catégories de dépenses',
        'tournees'           => 'Livraisons',
        'orders'             => 'Commandes',
        'reservations'       => 'Réservations',
        'achats'             => 'Achats',
        'inventaires'        => 'Inventaires',
        'stock'              => 'Stock',
        'stock-mouvements'   => 'Mouvements de stock',
        'banner'             => 'Bannière',
        'rapports'           => 'Rapports',
    ],
];