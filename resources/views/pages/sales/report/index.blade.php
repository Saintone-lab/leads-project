@extends('layouts.sales.app')
@section('title', 'report')
@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Assigned: Miss Regita</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                        <th>Total</th>
                        <th>Presentase</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <td>
                            <strong>Daily Call</strong>
                        </td>
                        <td>50</td>
                        <td>51</td>
                        <td>45</td>
                        <td>50</td>
                        <td>55</td>
                        <td>306</td>
                        <td>101%</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Presentation / Visit</strong>
                        </td>
                        <td>50</td>
                        <td>51</td>
                        <td>45</td>
                        <td>50</td>
                        <td>55</td>
                        <td>306</td>
                        <td>101%</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Quotation</strong>
                        </td>
                        <td>50</td>
                        <td>51</td>
                        <td>45</td>
                        <td>50</td>
                        <td>55</td>
                        <td>306</td>
                        <td>101%</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Prucashing Order</strong>
                        </td>
                        <td>50</td>
                        <td>51</td>
                        <td>45</td>
                        <td>50</td>
                        <td>55</td>
                        <td>306</td>
                        <td>101%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <p class="fw-semibold m-0"> Total Quotation</p>
                            <p class="text-muted m-0">50</p>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <p class="fw-semibold m-0"> Total PO</p>
                            <p class="text-muted m-0">50</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
