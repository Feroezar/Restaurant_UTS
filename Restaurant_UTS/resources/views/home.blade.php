@extends('master')

@section('konten')
  <h4>Selamat Datang <b>{{Auth::user()->name}}</b>, Anda Login sebagai <b>{{Auth::user()->role}}</b>.</h4>
  <a href="{{ route('minuman.index') }}" class="btn btn-md btn-success mb-3">Data Minuman</a>
  <a href="{{ route('makanan.index') }}" class="btn btn-md btn-success mb-3">Data Makanan</a>

@endsection