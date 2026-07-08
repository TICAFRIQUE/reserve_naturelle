@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et boutons -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-user-circle" style="color: #2d5a27; margin-right: 10px;"></i>
                        Détails de l'utilisateur
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-user" style="color: #2d5a27; margin-right: 5px;"></i>
                        Consultation des informations de l'utilisateur
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.users.edit', $user->id) }}" style="
                        background: #2d5a27;
                        color: white;
                        padding: 12px 24px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('admin.users.index') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 12px 24px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <!-- Messages flash -->
            @if(session('success'))
                <div style="
                    background: #d4edda;
                    color: #155724;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #28a745;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Carte d'informations -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
                max-width: 800px;
                margin: 0 auto;
            ">
                <!-- En-tête de la carte -->
                <div style="
                    padding: 20px 25px;
                    border-bottom: 1px solid #e8e0d5;
                    background: #faf8f5;
                    display: flex;
                    align-items: center;
                    gap: 16px;
                ">
                    <div style="
                        width: 60px;
                        height: 60px;
                        border-radius: 50%;
                        background: {{ $user->role === 'admin' ? '#2d5a27' : ($user->role === 'fournisseur' ? '#b8860b' : '#6c757d') }};
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 22px;
                        font-weight: bold;
                        flex-shrink: 0;
                    ">
                        {{ strtoupper(substr($user->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($user->nom ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <h4 style="color: #2d5a27; margin: 0; font-size: 1.3rem;">
                            {{ $user->full_name }}
                        </h4>
                        <div style="color: #6c757d; font-size: 0.9rem;">
                            <i class="fas fa-envelope" style="color: #2d5a27;"></i>
                            {{ $user->email }}
                            @if($user->email_verified_at)
                                <span style="
                                    background: #d4edda;
                                    color: #155724;
                                    padding: 2px 10px;
                                    border-radius: 12px;
                                    font-size: 0.7rem;
                                    margin-left: 6px;
                                    display: inline-block;
                                ">
                                    <i class="fas fa-check-circle"></i> Vérifié
                                </span>
                            @endif
                        </div>
                    </div>
                    <div style="margin-left: auto;">
                        @php
                            $roleColors = [
                                'admin' => ['bg' => '#2d5a27', 'text' => 'white', 'icon' => 'fa-user-shield'],
                                'fournisseur' => ['bg' => '#b8860b', 'text' => 'white', 'icon' => 'fa-truck'],
                                'user' => ['bg' => '#e8e0d5', 'text' => '#2d5a27', 'icon' => 'fa-user']
                            ];
                            $config = $roleColors[$user->role] ?? ['bg' => '#6c757d', 'text' => 'white', 'icon' => 'fa-user'];
                        @endphp
                        <span style="
                            background: {{ $config['bg'] }};
                            color: {{ $config['text'] }};
                            padding: 6px 18px;
                            border-radius: 20px;
                            font-size: 0.85rem;
                            font-weight: 600;
                            display: inline-block;
                        ">
                            <i class="fas {{ $config['icon'] }}" style="margin-right: 6px;"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div style="padding: 25px 30px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27; width: 35%;">
                                <i class="fas fa-hashtag" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                ID
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">#{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-user" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Nom complet
                            </td>
                            <td style="padding: 10px 0; color: #2d5a27; font-weight: 500;">{{ $user->full_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-envelope" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Email
                            </td>
                            <td style="padding: 10px 0;">
                                <a href="mailto:{{ $user->email }}" style="color: #2d5a27; text-decoration: none;">
                                    {{ $user->email }}
                                </a>
                                @if($user->email_verified_at)
                                    <span style="
                                        background: #d4edda;
                                        color: #155724;
                                        padding: 2px 10px;
                                        border-radius: 12px;
                                        font-size: 0.7rem;
                                        margin-left: 8px;
                                        display: inline-block;
                                    ">
                                        <i class="fas fa-check-circle"></i> Vérifié
                                    </span>
                                @else
                                    <span style="
                                        background: #fff3cd;
                                        color: #856404;
                                        padding: 2px 10px;
                                        border-radius: 12px;
                                        font-size: 0.7rem;
                                        margin-left: 8px;
                                        display: inline-block;
                                    ">
                                        <i class="fas fa-clock"></i> Non vérifié
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-phone" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Téléphone
                            </td>
                            <td style="padding: 10px 0;">
                                @if($user->tel)
                                    <a href="tel:{{ $user->tel }}" style="color: #2d5a27; text-decoration: none;">
                                        {{ $user->tel }}
                                    </a>
                                @else
                                    <span style="color: #adb5bd;">Non renseigné</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-tag" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Rôle
                            </td>
                            <td style="padding: 10px 0;">
                                <span style="
                                    background: {{ $config['bg'] }};
                                    color: {{ $config['text'] }};
                                    padding: 4px 14px;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 500;
                                    display: inline-block;
                                ">
                                    <i class="fas {{ $config['icon'] }}" style="margin-right: 5px;"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-calendar-plus" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Inscrit le
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600; color: #2d5a27;">
                                <i class="fas fa-clock" style="color: #2d5a27; width: 20px; margin-right: 8px;"></i>
                                Modifié le
                            </td>
                            <td style="padding: 10px 0; color: #6c757d;">{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Pied de carte avec actions -->
                <div style="
                    padding: 15px 25px;
                    border-top: 1px solid #e8e0d5;
                    background: #faf8f5;
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                ">
                    <a href="{{ route('admin.users.edit', $user->id) }}" style="
                        background: #2d5a27;
                        color: white;
                        padding: 10px 25px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 0.95rem;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('admin.users.index') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 10px 25px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 0.95rem;
                    " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <!-- Statistiques de l'utilisateur -->
            <div style="
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                max-width: 800px;
                margin: 30px auto 0;
            ">
                <!-- Commandes -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    transition: transform 0.3s;
                " onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: #e3f2fd;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 10px;
                    ">
                        <i class="fas fa-shopping-cart" style="font-size: 22px; color: #1565c0;"></i>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #2d5a27;">{{ $user->orders->count() }}</div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Commandes</div>
                </div>

                <!-- Achats -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    transition: transform 0.3s;
                " onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: #e8f5e9;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 10px;
                    ">
                        <i class="fas fa-shopping-bag" style="font-size: 22px; color: #2d5a27;"></i>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #2d5a27;">{{ $user->achats->count() }}</div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Achats</div>
                </div>

                <!-- Paniers -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    transition: transform 0.3s;
                " onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: #fff8e1;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 10px;
                    ">
                        <i class="fas fa-shopping-basket" style="font-size: 22px; color: #b8860b;"></i>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #2d5a27;">{{ $user->carts->count() }}</div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Paniers</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px 15px !important;
        }
        .container > div > div:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .container > div > div:first-child > div:last-child {
            width: 100%;
        }
        .container > div > div:first-child > div:last-child a {
            flex: 1;
            justify-content: center;
        }
        .container > div > div:nth-child(2) {
            flex-wrap: wrap !important;
        }
        .container > div > div:nth-child(2) > div:last-child {
            flex-wrap: wrap !important;
        }
        .container > div > div:nth-child(2) > div:last-child > div:last-child {
            margin-left: 0 !important;
        }
        .container > div > div:last-child {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
        }
        .container > div > div:nth-child(3) > div:last-child table td {
            padding: 8px 0 !important;
        }
        .container > div > div:nth-child(3) > div:last-child {
            padding: 20px !important;
        }
        .container > div > div:nth-child(3) > div:last-child table td:first-child {
            width: 40% !important;
        }
        .container > div > div:nth-child(3) > div:last-child table td:last-child {
            word-break: break-word;
        }
    }

    @media (max-width: 480px) {
        .container > div > div:nth-child(2) > div:last-child {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .container > div > div:nth-child(2) > div:last-child > div:last-child {
            text-align: left !important;
            padding-top: 10px;
            border-top: 1px solid #e8e0d5;
        }
        .container > div > div:nth-child(2) > div:last-child > div:last-child span {
            display: block;
            text-align: center;
        }
        .container > div > div:last-child {
            grid-template-columns: 1fr 1fr !important;
        }
        .container > div > div:last-child > div:last-child {
            grid-column: span 2;
        }
    }
</style>
@endpush