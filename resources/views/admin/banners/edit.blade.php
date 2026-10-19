@extends('layouts.admin')

@section('title', 'Modifier la bannière')

@section('content')

<div class="admin-main">

    <!-- HERO -->
    <div class="admin-hero">
        <div class="hero-left">
            <div class="hero-text">
                <h1>Modifier la bannière d'accueil</h1>
                <p>Gérez le contenu et les images du slider</p>
            </div>
        </div>
        <div class="hero-right">
            <a href="{{ route('home') }}" target="_blank" class="btn-back">
                <i class="fas fa-eye"></i> Voir le rendu
            </a>
        </div>
    </div>

    <!-- MESSAGES -->
    @if(session('success'))
        <div class="alert-success" style="background:#d4edda;color:#155724;padding:15px;border-radius:8px;margin-bottom:20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error" style="background:#f8d7da;color:#721c24;padding:15px;border-radius:8px;margin-bottom:20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- FORMULAIRE PRINCIPAL (Textes + Image principale) -->
    <form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid-2cols">

            <!-- COLONNE GAUCHE : CONTENU -->
            <div class="finance-card">
                <h4><i class="fas fa-edit"></i> Contenu de la bannière</h4>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Titre principal</label>
                    <input type="text" name="titre" value="{{ old('titre', $banner->titre) }}" required
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                    @error('titre')
                        <span style="color:var(--red);font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Partie colorée du titre</label>
                    <input type="text" name="titre_span" value="{{ old('titre_span', $banner->titre_span) }}"
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                    <small style="color:var(--gray-500);font-size:12px;">Ex: "terre ivoirienne" (affiché en or)</small>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Sous-titre</label>
                    <textarea name="sous_titre" rows="3"
                              style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;resize:vertical;">{{ old('sous_titre', $banner->sous_titre) }}</textarea>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Texte du bouton</label>
                    <input type="text" name="texte_bouton" value="{{ old('texte_bouton', $banner->texte_bouton) }}"
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Lien du bouton</label>
                    <input type="text" name="lien_bouton" value="{{ old('lien_bouton', $banner->lien_bouton) }}"
                           style="width:100%;padding:10px 14px;border:1px solid var(--gray-200);border-radius:8px;font-family:inherit;">
                    <small style="color:var(--gray-500);font-size:12px;">Ex: #catalogue, /produits, https://...</small>
                </div>
            </div>

            <!-- COLONNE DROITE : IMAGE PRINCIPALE + STATUT -->
            <div class="finance-card">
                <h4><i class="fas fa-star"></i> Image principale (1er slide)</h4>

                <p style="color:var(--gray-500);font-size:13px;margin-bottom:16px;">
                    Cette image sera le <strong>1er slide</strong> du slider.
                </p>

                @if($banner->image_path)
                    <div style="margin-bottom:16px;">
                        <img src="{{ asset('storage/' . $banner->image_path) }}"
                             style="max-width:100%;border-radius:8px;display:block;">
                    </div>
                @else
                    <div style="padding:40px;background:var(--gray-50);border-radius:8px;text-align:center;color:var(--gray-400);margin-bottom:16px;">
                        <i class="fas fa-image" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                        Aucune image
                    </div>
                @endif

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;margin-bottom:6px;">Changer l'image principale</label>
                    <input type="file" name="image" accept="image/*"
                           style="width:100%;padding:10px;border:1px solid var(--gray-200);border-radius:8px;">
                    <small style="color:var(--gray-500);font-size:12px;">Format recommandé : 1920×800, max 5MB</small>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="actif" value="1" {{ $banner->actif ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--green-primary);">
                        <span style="font-weight:600;">Bannière active</span>
                    </label>
                    <small style="color:var(--gray-500);font-size:12px;">Décochez pour masquer la bannière</small>
                </div>

                <button type="submit"
                        style="width:100%;padding:14px;background:var(--green-primary);color:white;border:none;border-radius:8px;font-weight:700;font-size:15px;cursor:pointer;">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>

        </div>
    </form>

    <!-- ============================================
         SECTION SLIDES SUPPLÉMENTAIRES
    ============================================ -->
    <div class="finance-card" style="margin-top:30px;">

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px;margin-bottom:20px;">
            <div>
                <h4 style="margin:0;"><i class="fas fa-images"></i> Slides supplémentaires</h4>
                <p style="color:var(--gray-500);font-size:13px;margin:6px 0 0;">
                    Ajoutez d'autres images qui défileront après l'image principale.
                </p>
            </div>
            <span style="background:var(--green-primary);color:white;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;">
                {{ $slides->count() }} slide(s)
            </span>
        </div>

        <!-- FORMULAIRE D'UPLOAD MULTIPLE -->
        <form action="{{ route('admin.banner.slides.store') }}"
              method="POST"
              enctype="multipart/form-data"
              style="margin-bottom:30px;">
            @csrf

            <div style="padding:25px;border:2px dashed var(--green-primary);border-radius:12px;background:#f8fff9;">
                <div style="display:flex;flex-direction:column;gap:15px;align-items:center;text-align:center;">

                    <div style="font-size:42px;color:var(--green-primary);opacity:0.5;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>

                    <div>
                        <p style="font-weight:700;font-size:15px;margin:0 0 6px;color:var(--gray-700);">
                            Glissez vos images ici ou cliquez pour sélectionner
                        </p>
                        <p style="color:var(--gray-500);font-size:13px;margin:0;">
                            Formats : JPG, PNG, WebP · Max 5 MB par image · Recommandé 1920×800
                        </p>
                    </div>

                    <input type="file"
                           id="images-upload"
                           name="images[]"
                           accept="image/*"
                           multiple
                           required
                           style="display:none;">

                    <label for="images-upload"
                           style="cursor:pointer;background:var(--green-primary);color:white;padding:12px 28px;border-radius:8px;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:10px;">
                        <i class="fas fa-folder-open"></i>
                        Choisir des images
                    </label>

                    <!-- Aperçu des fichiers sélectionnés -->
                    <div id="files-preview" style="display:none;width:100%;margin-top:10px;">
                        <p style="font-size:13px;font-weight:600;color:var(--green-primary);margin-bottom:10px;">
                            <i class="fas fa-check-circle"></i> <span id="files-count">0</span> fichier(s) sélectionné(s)
                        </p>
                        <div id="files-list" style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;"></div>
                    </div>

                    <button type="submit"
                            id="submit-slides"
                            style="display:none;background:var(--green-primary);color:white;padding:14px 32px;border:none;border-radius:8px;font-weight:700;font-size:15px;cursor:pointer;align-items:center;gap:10px;">
                        <i class="fas fa-upload"></i> Uploader les images
                    </button>

                </div>
            </div>
        </form>

        <!-- LISTE DES SLIDES EXISTANTS -->
        @if($slides->count() > 0)
            <h5 style="font-size:14px;color:var(--gray-700);margin:25px 0 15px;font-weight:700;">
                Slides existants (ordre d'affichage)
            </h5>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;">

                @foreach($slides as $slide)
                    <div style="border:2px solid {{ $slide->actif ? 'var(--green-primary)' : 'var(--gray-200)' }};
                                border-radius:12px;
                                overflow:hidden;
                                background:white;
                                opacity:{{ $slide->actif ? 1 : 0.55 }};
                                transition:all 0.3s;">

                        <!-- Image -->
                        <div style="position:relative;padding-top:56%;background:#f5f5f5;">
                            <img src="{{ $slide->image_url }}"
                                 alt="Slide"
                                 style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;">

                            <!-- Badge ordre -->
                            <span style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,0.7);color:white;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                #{{ $loop->iteration + 1 }}
                            </span>

                            @if(!$slide->actif)
                                <span style="position:absolute;top:8px;right:8px;background:#B83232;color:white;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                    INACTIF
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div style="padding:12px;display:flex;gap:8px;align-items:center;justify-content:space-between;">

                            <span style="font-size:12px;color:var(--gray-500);font-weight:600;">
                                {{ $slide->actif ? '✓ Actif' : '✗ Inactif' }}
                            </span>

                            <div style="display:flex;gap:6px;">

                                <!-- Toggle actif -->
                                <form action="{{ route('admin.banner.slides.toggle', $slide) }}"
                                      method="POST"
                                      style="margin:0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            title="{{ $slide->actif ? 'Désactiver' : 'Activer' }}"
                                            style="background:{{ $slide->actif ? '#d4edda' : '#f8d7da' }};
                                                   color:{{ $slide->actif ? '#155724' : '#721c24' }};
                                                   border:none;width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-{{ $slide->actif ? 'eye' : 'eye-slash' }}"></i>
                                    </button>
                                </form>

                                <!-- Supprimer -->
                                <form action="{{ route('admin.banner.slides.destroy', $slide) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer ce slide définitivement ?');"
                                      style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Supprimer"
                                            style="background:#f8d7da;color:#721c24;border:none;width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @else
            <div style="text-align:center;padding:40px 20px;background:var(--gray-50);border-radius:12px;">
                <div style="font-size:48px;color:var(--gray-400);margin-bottom:15px;">
                    <i class="fas fa-images"></i>
                </div>
                <p style="font-weight:700;color:var(--gray-700);margin:0 0 6px;">
                    Aucun slide supplémentaire
                </p>
                <p style="color:var(--gray-500);font-size:13px;margin:0;">
                    Uploadez vos premières images ci-dessus pour animer le slider.
                </p>
            </div>
        @endif

    </div>

</div>

@endsection

@push('scripts')
<script>
  // Aperçu des fichiers sélectionnés
  (function() {
    const input = document.getElementById('images-upload');
    const preview = document.getElementById('files-preview');
    const list = document.getElementById('files-list');
    const count = document.getElementById('files-count');
    const submit = document.getElementById('submit-slides');

    if (!input) return;

    input.addEventListener('change', function() {
      const files = Array.from(this.files);

      list.innerHTML = '';
      count.textContent = files.length;

      files.forEach(file => {
        const tag = document.createElement('span');
        tag.textContent = file.name;
        tag.style.cssText = 'background:#e8f5e9;color:#2d5a27;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;';
        list.appendChild(tag);
      });

      if (files.length > 0) {
        preview.style.display = 'block';
        submit.style.display = 'inline-flex';
      } else {
        preview.style.display = 'none';
        submit.style.display = 'none';
      }
    });
  })();
</script>
@endpush