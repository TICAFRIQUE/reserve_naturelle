@props(['productId', 'qteInputId' => null, 'label' => '🛒 Ajouter'])

<button type="button"
        class="add-to-cart-btn"
        data-product-id="{{ $productId }}"
        @if($qteInputId) data-qte-input="{{ $qteInputId }}" @endif
        {{ $attributes->merge(['style' => 'background: var(--green); color: white; padding: 6px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;']) }}>
    {{ $label }}
</button>