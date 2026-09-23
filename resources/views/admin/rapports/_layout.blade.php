{{-- resources/views/admin/rapports/_layout.blade.php --}}
{{-- Chaque page d'onglet fait: @extends('admin.rapports._layout') puis @section('tableau') ... @endsection --}}
@extends('layouts.admin')

@section('title', 'Rapports - Gestion de stock - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            @include('admin.rapports._entete_minimal')

            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">
                @yield('tableau')
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination { display: flex; list-style: none; gap: 5px; padding: 0; margin: 0; }
    .pagination li a, .pagination li span {
        display: inline-block; padding: 8px 16px; background: white; border: 1px solid #e8e0d5;
        border-radius: 6px; color: #2d5a27; text-decoration: none; font-size: 0.9rem;
    }
    .pagination li.active span { background: #2d5a27; color: white; border-color: #2d5a27; }
    .pagination li.disabled span { color: #adb5bd; background: #f8f5f0; }

    @media print {
        .admin-sidebar, .admin-sidebar-overlay, header.header, .admin-footer,
        form, .pagination, button, a[href*="export"] { display: none !important; }
        .admin-main-wrapper, .admin-main { margin: 0 !important; padding: 0 !important; }
        body { background: white !important; }
        table { font-size: 10px !important; }
    }
</style>
@endpush