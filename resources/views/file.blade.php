@extends('layout.app', [
    'title' => 'Upload PDF',
])

@section('content')
    <div class="container">
        <div>
            <form action="{{ route('file.readFile') }}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('POST')

                <div class="mb-2">
                    <label for="file">Aquivo:</label>
                    <input type="file" class="@if ($errors->first('file')) {{ 'is-invalid' }} @endif" name="file">
                    @if ($errors->first('file'))
                        <div class="invalid-feedback">{{ $errors->first('file') }}</div>
                    @endif
                </div>
                <div class="d-flex row w-auto">
                    <b>Escolha o formato para exportar</b>
                    <label for="csv">Exportar para CSV</label>
                    <input type="radio" name="choosenType" value="csv" id="csv" checked>

                    <label for="xlsx">Exportar para Excel (.xlsx)</label>
                    <input type="radio" name="choosenType" value="xlsx" id="xlsx">
                    @if ($errors->first('choosenType'))
                        <div class="invalid-feedback">{{ $errors->first('choosenType') }}</div>
                    @endif
                </div>
                <div>
                    <button class="btn btn-outline-primary" type="submit">
                        Exportar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
