@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<style>
    body {
        background: url('{{ asset('images/funder2.png') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
        position: relative;
    }

    /* Capa con blur accesible */
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.30);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: -1;
    }

    .login-card {
        background-color: rgba(255, 255, 255, 0.96);
        border-radius: 1.5rem;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
        transform: translateY(25px);
        padding: 2rem;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-logo img {
        width: 120px;
        margin-bottom: 10px;
    }

    .input-group-text {
        background-color: #e9ecef;
        color: #212529;
        border: none;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .form-control {
        border: 2px solid #1b263b;
        border-radius: 0 0.5rem 0.5rem 0;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.15);
        outline: none;
    }

    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        color: white !important;
        border-radius: 0.75rem;
        font-weight: bold;
        font-size: 1.05rem;
        padding: 0.8rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #49932f;
        transform: translateY(-2px);
    }

    .btn-primary:active {
        background-color: #2D6A4F !important;
    }

    .invalid-feedback {
        font-size: 0.875rem;
    }

    .toggle-visibility {
        cursor: pointer;
        padding: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@stop
