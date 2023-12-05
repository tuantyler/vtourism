@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <button type="button" class="btn btn-info btn-sm w-100" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fa fa-plus"></i> Tạo dự án mới
    </button>
    <div class="modal fade" id="createModal" tabindex="-1">
        <form method="POST" action="{{route('newProject')}}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <input class="form-control" placeholder="Tên dự án mới" name="projectName" required>
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

    @foreach ($projects as $project)
    <div class="project_placeholder">
        <a href="/edit_project/{{$project->id}}">
            <h5 class="m-0 fw-bold">{{$project->projectName}}</h5>
            <small>Lần chỉnh sửa cuối cùng: <small class="fw-bold">{{$project->lastEdit ? $project->lastEdit : "chưa có"}}</small></small>
        </a>
        <a class="btn btn-danger btn-sm float-end" href="/delete_project/{{$project->id}}">Xóa <i class="fa fa-trash"></i></a>
  
    </div>
    @endforeach

</div>
@endsection