<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DocumentationController extends Controller
{
    /** Guide d'utilisation : tout le personnel autorisé (middleware admin) */
    public function guide(){
        return $this->pdf('guide-utilisation.pdf');
    }

    /** Documentation technique : réservée au rôle admin (voir routes) */
    public function technique(){
        return $this->pdf('documentation-technique.pdf');
    }

    private function pdf(string $file){
        $path = resource_path('docs/' . $file);
        abort_unless(is_file($path), 404, 'Document introuvable.');

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file . '"',
        ]);
    }
}