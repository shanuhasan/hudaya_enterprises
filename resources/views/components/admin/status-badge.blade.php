{{-- <x-admin.status-badge :status="$inquiry->status" /> — InquiryStatus enum --}}
@props(['status'])
@php
    $class = match ($status) {
        \App\Enums\InquiryStatus::New => 'adm-badge--new',
        \App\Enums\InquiryStatus::Read => 'adm-badge--read',
        \App\Enums\InquiryStatus::Replied => 'adm-badge--replied',
        \App\Enums\InquiryStatus::Archived => 'adm-badge--archived',
    };
@endphp
<span class="adm-badge {{ $class }}">{{ $status->label() }}</span>
