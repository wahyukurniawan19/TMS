@extends('layout.base_layout')

@section('content')

    @include('layout.sidebar_nav')

    <div class="col">

        <div class="border-bottom my-3">
            <h3 class="page_title">
                Edit Repository
            </h3>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="base_block shadow-sm p-4 rounded">
            <form method="POST" action="{{route('repository_update')}}">
                @csrf
                <input type="hidden" name="id" value="{{$repository->id}}">

                <div class="mb-3">
                    <label for="title" class="form-label">Name</label>
                    <input name="title" type="text" class="form-control" required value="{{$repository->title}}" maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="prefix" class="form-label">Prefix <span class="text-muted">(max 3 symbols)</span></label>
                    <input type="text" class="form-control" name="prefix" required value="{{$repository->prefix}}" maxlength="3"
                           pattern="[^\s]+" title="please don't use whitespace" style="text-transform:uppercase">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" maxlength="255">{{$repository->description}}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4 me-2">
                        Save
                    </button>

                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection
