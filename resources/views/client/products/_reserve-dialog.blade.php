<dialog class="reserve-dialog" id="reserve-dialog-{{ $product->id }}" 
        style="border:none; border-radius:16px; padding:0; max-width:460px; width:100%; margin-top:100px; margin-left:450px; box-shadow:0 10px 30px rgba(0,0,0,0.15);">
    
    <div style="background:#fff; border-radius:16px; overflow:hidden;">
        {{-- En-tête --}}
        <div style="background:#2d5a27; padding:18px 24px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-family:'Playfair Display',serif; color:#fff; margin:0; font-size:1.25rem;">
                <i class="far fa-clock"></i> Réserver ce produit
            </h3>
            <button type="button" data-reserve-close
                    style="background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer; line-height:1;">
                &times;
            </button>
        </div>

        {{-- Corps --}}
        <form method="POST" action="{{ route('client.reservations.store') }}" style="padding:24px; display:grid; gap:16px;">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <p style="color:#666; font-size:0.9rem; margin:0;">
                <strong style="color:#2d5a27;">{{ $product->designation }}</strong> est actuellement en rupture 
                (ou pour certaines options). Réservez-le et nous vous préviendrons dès son retour.
            </p>

            {{-- Variante --}}
            @if($product->variants->count() > 1)
                <div>
                    <label for="reserve-variant-{{ $product->id }}" 
                           style="display:block; font-weight:600; color:#2d5a27; font-size:0.9rem; margin-bottom:5px;">
                        Conditionnement
                    </label>
                    <select name="product_variant_id" id="reserve-variant-{{ $product->id }}" required 
                            style="width:100%; padding:10px 15px; border:1px solid #e8e0d5; border-radius:8px; background:#f8f5f0; font-size:0.95rem;">
                        <option value="">-- Choisir --</option>
                        @foreach($product->variants as $v)
                            <option value="{{ $v->id }}" @selected($v->qte_dispo <= 0)>
                                {{ $v->conditionnement }} — {{ number_format($v->prix_vente, 0, ',', ' ') }} FCFA
                                {{ $v->qte_dispo <= 0 ? ' (en rupture)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif($product->variants->count() === 1)
                <input type="hidden" name="product_variant_id" value="{{ $product->variants->first()->id }}">
            @endif

            {{-- Quantité --}}
            <div>
                <label for="reserve-qte-{{ $product->id }}" 
                       style="display:block; font-weight:600; color:#2d5a27; font-size:0.9rem; margin-bottom:5px;">
                    Quantité souhaitée
                </label>
                <input type="number" name="qte" id="reserve-qte-{{ $product->id }}" 
                       min="1" max="1000" value="1" required
                       style="width:100%; padding:10px 15px; border:1px solid #e8e0d5; border-radius:8px; background:#f8f5f0; font-size:0.95rem;">
            </div>

            {{-- Note --}}
            <div>
                {{-- <label for="reserve-note-{{ $product->id }}" 
                       style="display:block; font-weight:600; color:#2d5a27; font-size:0.9rem; margin-bottom:5px;">
                    Note (facultatif)
                </label> --}}
                <textarea name="note" id="reserve-note-{{ $product->id }}" rows="2" maxlength="500"
                          placeholder="Une précision pour votre réservation..."
                          style="width:100%; padding:10px 15px; border:1px solid #e8e0d5; border-radius:8px; background:#f8f5f0; font-size:0.95rem; resize:vertical;"></textarea>
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
                <button type="button" data-reserve-close
                        style="background:#e8e0d5; color:#2d5a27; border:none; padding:10px 24px; border-radius:30px; cursor:pointer; font-weight:500;">
                    Annuler
                </button>
                <button type="submit"
                        style="background:#2d5a27; color:#fff; border:none; padding:10px 24px; border-radius:30px; cursor:pointer; font-weight:500;">
                    <i class="far fa-clock"></i> Confirmer la réservation
                </button>
            </div>
        </form>
    </div>
</dialog>