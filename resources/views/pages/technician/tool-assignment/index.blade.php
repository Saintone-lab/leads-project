@extends('layouts.sales.app')
@section('title', 'Management Tools per Teknisi')
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h4 class="fw-bold py-3 mb-4">
        Management Tools per Teknisi
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Teknisi</th>
                                <th>Code</th>
                                <th>Total Tools Aktif</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($technicians as $technician)
                                <tr>
                                    <td>{{ $technician->name }}</td>
                                    <td>{{ $technician->code ?? '-' }}</td>
                                    <td>{{ $technician->tools_assigned_count }}</td>
                                    <td>
                                        <a href="{{ route('tool-assignment.show', $technician->id) }}"
                                            class="btn btn-sm btn-primary">Kelola Tools</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada user dengan role Technician.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection()
