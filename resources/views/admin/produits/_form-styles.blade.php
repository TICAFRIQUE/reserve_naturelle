<style>
    .form-section { margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #f0ebe5; }
    .form-section:last-of-type { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .form-section-title {
        font-size: 0.9rem; font-weight: 700; color: #b8860b;
        text-transform: uppercase; letter-spacing: 0.5px;
        margin: 0 0 18px 0; display: flex; align-items: center; gap: 8px;
    }
    .field-error { color: #dc3545; font-size: 0.85rem; margin-top: 5px; }
    .field-hint { color: #6c757d; font-size: 0.8rem; margin-top: 5px; }
    .field-label { display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem; }
    .field-input {
        width: 100%; padding: 12px 16px; border-radius: 8px; font-size: 1rem;
        transition: border-color 0.3s; outline: none; background: #faf8f5;
    }

    @media (max-width: 768px) {
        .container { padding: 20px 15px !important; }
        .page-header { flex-direction: column !important; align-items: flex-start !important; }
        .page-header > div:last-child { width: 100%; }
        .page-header > div:last-child a { width: 100%; justify-content: center; }
        .form-card { padding: 25px 20px !important; }
        .grid-2 { grid-template-columns: 1fr !important; gap: 15px !important; }
        .form-actions { flex-direction: column !important; }
        .form-actions a, .form-actions button { width: 100%; justify-content: center; }
        input[type="number"] { max-width: 100% !important; }
    }
</style>