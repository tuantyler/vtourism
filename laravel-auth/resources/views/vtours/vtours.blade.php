@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <button type="button" class="btn btn-info btn-sm w-100" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fa fa-plus"></i> Tạo vtour mới
    </button>
    <div class="modal fade" id="createModal" tabindex="-1">
        <form method="POST" action="{{route('new_vtours')}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <input class="form-control" placeholder="Tên vtour mới" name="name" required>
                        <input type="file" class="form-control" placeholder="Upload video" name="videoPath" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tạo</button>
                    </div>
                </div>
            </div>
    </div>
    </form>
    <br><br>
    @foreach ($vtours as $vtour)
        <a {!!$vtour->processed == 1 ? 'href="/edit_vtour/' . $vtour->id . '"' : " "!!} >
            <div class="project_placeholder">   
                <span class="btn btn-light btn-sm float-end">{!!$vtour->processed == 1 ? '<i class="fa fa-eye" aria-hidden="true"></i> Đã xử lý' : '<i class="fa fa-eye-slash" aria-hidden="true"></i> Đang xử lý</a>'!!}</span>
                <h5 class="m-0 fw-bold"> {{$vtour->name}} </h5>
                <a class="btn btn-danger btn-sm" href="/delete_vtour/{{$vtour->id}}"><i class="fa fa-trash"></i></a>
                <small>Lần chỉnh sửa cuối cùng: <small class="fw-bold">{{$vtour->lastEdit ? $vtour->lastEdit : "chưa có"}}</small></small>
            </div>
        </a>
        @endforeach
</div>
@endsection