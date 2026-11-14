@props(['productVariantId', 'qteInputId' => null, 'label' => '🛒 Ajouter'])

<button type="button"
        class="add-to-cart-btn"
        data-product-variant-id="{{ $productVariantId }}"
        data-cart-url="{{ route('client.cart.store') }}"
        @if($qteInputId) data-qte-input="{{ $qteInputId }}" @endif
        {{ $attributes->merge(['style' => 'background: var(--green); color: white; padding: 6px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;']) }}>
    {{ $label }}
</button>