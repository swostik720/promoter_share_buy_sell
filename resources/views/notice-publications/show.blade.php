@extends('layouts.app')

@section('title', 'Notice Publication Details - Promoter Share Management')
@section('page-title', 'Notice Publication Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notice Publication #{{ $notice->id }}</h5>
                <div>
                    <a href="{{ route('notice-publications.edit', $notice->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('notice-publications.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Publication Date:</th>
                                <td>{{ $notice->publication_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Newspaper:</th>
                                <td><strong>{{ $notice->newspaper_name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Notice Reference:</th>
                                <td>{{ $notice->notice_reference ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $notice->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Related Sell Application</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Seller:</th>
                                <td><strong>{{ $notice->sellApplication->seller->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Application Date:</th>
                                <td>{{ $notice->sellApplication->application_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Share Quantity:</th>
                                <td>{{ number_format($notice->sellApplication->share_quantity_to_sell) }}</td>
                            </tr>
                            <tr>
                                <th>Current Status:</th>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst(str_replace('_', ' ', $notice->sellApplication->status)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('sell-applications.show', $notice->sellApplication->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye me-1"></i>View Sell Application
                        </a>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Published Notice Content</h6>
                        <div class="border p-4 bg-light rounded" style="white-space: pre-line; font-family: 'Times New Roman', serif;">
                            {{ $notice->notice_content }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
