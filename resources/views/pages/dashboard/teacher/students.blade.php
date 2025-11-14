@extends('layouts.dashboard')
@section('title', 'Kelola Tabungan Siswa - Dashboard Guru Ambalance')
@if (App::isLocale('en'))
    {{-- English --}}
    @section('meta-description', 'Manage student savings and view student list on the Ambalance Teacher Dashboard. Monitor financial activity and deposits.')
    @section('meta-keywords', 'teacher dashboard, student savings, student list, manage deposits, ambalance, school finance management')
@else
    {{-- Default ID --}}
    @section('meta-description', 'Kelola tabungan siswa dan lihat daftar lengkap siswa di Dashboard Guru Ambalance. Pantau riwayat transaksi, setor, dan tarik tabungan siswa dengan mudah.')
    @section('meta-keywords', 'dashboard guru, kelola tabungan siswa, daftar siswa, setor tabungan, tarik tabungan, manajemen keuangan sekolah, ambalance')
@endif
@section('content')

@endsection
