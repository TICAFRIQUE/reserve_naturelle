function formatNumber(n) {
    return new Intl.NumberFormat('fr-FR').format(n);
}

function recalculerTotaux() {
    let total = 0;
    document.querySelectorAll('.ligne-achat').forEach(function(ligne) {
        const qte  = parseFloat(ligne.querySelector('.input-qte').value) || 0;
        const prix = parseFloat(ligne.querySelector('.input-prix').value) || 0;
        const sous = qte * prix;
        ligne.querySelector('.sous-total').textContent = formatNumber(sous) + ' FCFA';
        total += sous;
    });
    document.getElementById('totalGeneral').textContent = formatNumber(total);
}

function buildOptions(produits) {
    let html = '<option value="">— Choisir —</option>';
    produits.forEach(function(p) {
        html += '<option value="' + p.id + '" data-stock="' + p.stock + '" data-cmp="' + p.cmp + '">' + p.designation + '</option>';
    });
    return html;
}

function creerLigne(index, produits) {
    const div = document.createElement('div');
    div.className = 'ligne-achat';
    div.style.cssText = 'display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:15px;align-items:end;padding:15px;background:#f8f5f0;border-radius:8px;margin-bottom:12px;';

    const col1 = document.createElement('div');
    col1.innerHTML = '<label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">Produit <span style="color:#dc3545;">*</span></label>';
    const select = document.createElement('select');
    select.name = 'lignes[' + index + '][product_id]';
    select.required = true;
    select.className = 'select-produit';
    select.style.cssText = 'width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;';
    select.innerHTML = buildOptions(produits);
    select.addEventListener('focus', function() { this.style.borderColor = '#2d5a27'; });
    select.addEventListener('blur',  function() { this.style.borderColor = '#e8e0d5'; });
    col1.appendChild(select);

    const col2 = document.createElement('div');
    col2.innerHTML = '<label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">Quantité <span style="color:#dc3545;">*</span></label>';
    const inputQte = document.createElement('input');
    inputQte.type = 'number';
    inputQte.name = 'lignes[' + index + '][qte_commandee]';
    inputQte.min = '1';
    inputQte.required = true;
    inputQte.placeholder = '0';
    inputQte.className = 'input-qte';
    inputQte.style.cssText = 'width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;';
    inputQte.addEventListener('focus', function() { this.style.borderColor = '#2d5a27'; });
    inputQte.addEventListener('blur',  function() { this.style.borderColor = '#e8e0d5'; });
    inputQte.addEventListener('input', recalculerTotaux);
    col2.appendChild(inputQte);

    const col3 = document.createElement('div');
    col3.innerHTML = '<label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">Prix unitaire (FCFA) <span style="color:#dc3545;">*</span></label>';
    const inputPrix = document.createElement('input');
    inputPrix.type = 'number';
    inputPrix.name = 'lignes[' + index + '][prix_unitaire]';
    inputPrix.min = '0';
    inputPrix.required = true;
    inputPrix.placeholder = '0';
    inputPrix.className = 'input-prix';
    inputPrix.style.cssText = 'width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;';
    inputPrix.addEventListener('focus', function() { this.style.borderColor = '#2d5a27'; });
    inputPrix.addEventListener('blur',  function() { this.style.borderColor = '#e8e0d5'; });
    inputPrix.addEventListener('input', recalculerTotaux);
    col3.appendChild(inputPrix);

    const col4 = document.createElement('div');
    col4.innerHTML = '<label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">Sous-total</label>';
    const span = document.createElement('span');
    span.className = 'sous-total';
    span.style.cssText = 'padding:10px 15px;background:#e8f5e9;border-radius:8px;font-weight:600;color:#2d5a27;white-space:nowrap;display:block;';
    span.textContent = '0 FCFA';
    col4.appendChild(span);

    const col5 = document.createElement('div');
    col5.style.paddingBottom = '2px';
    const btnSuppr = document.createElement('button');
    btnSuppr.type = 'button';
    btnSuppr.className = 'btn-suppr-ligne';
    btnSuppr.style.cssText = 'background:#f8d7da;color:#721c24;padding:10px 12px;border-radius:8px;border:none;cursor:pointer;';
    btnSuppr.title = 'Supprimer';
    btnSuppr.innerHTML = '<i class="fas fa-trash-alt"></i>';
    btnSuppr.addEventListener('click', function() {
        if (document.querySelectorAll('.ligne-achat').length > 1) {
            div.remove();
            recalculerTotaux();
        }
    });
    col5.appendChild(btnSuppr);

    div.appendChild(col1);
    div.appendChild(col2);
    div.appendChild(col3);
    div.appendChild(col4);
    div.appendChild(col5);

    return div;
}

function initAchatCreate(produits) {
    let ligneIndex = 1;

    // Bind sur la ligne initiale
    document.querySelectorAll('.ligne-achat').forEach(function(ligne) {
        ligne.querySelector('.input-qte').addEventListener('input', recalculerTotaux);
        ligne.querySelector('.input-prix').addEventListener('input', recalculerTotaux);
        ligne.querySelector('.btn-suppr-ligne').addEventListener('click', function() {
            if (document.querySelectorAll('.ligne-achat').length > 1) {
                ligne.remove();
                recalculerTotaux();
            }
        });
    });

    // Ajouter une ligne
    document.getElementById('btnAjouterLigne').addEventListener('click', function() {
        document.getElementById('lignesContainer').appendChild(creerLigne(ligneIndex++, produits));
        recalculerTotaux();
    });
}