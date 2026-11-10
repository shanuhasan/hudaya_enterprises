<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InquiryCategory;
use App\Enums\InquiryStatus;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $status = InquiryStatus::tryFrom((string) $request->input('status'));
        $category = InquiryCategory::tryFrom((string) $request->input('category'));

        $inquiries = Inquiry::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('company', 'like', $term)
                    ->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('product', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.inquiries.index', [
            'inquiries' => $inquiries,
            'status' => $status,
            'counts' => Inquiry::query()->select('status')->selectRaw('count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'total' => Inquiry::query()->count(),
        ]);
    }

    public function show(Inquiry $inquiry): View
    {
        $inquiry->markAsRead();

        return view('admin.inquiries.show', [
            'inquiry' => $inquiry,
            'previous' => Inquiry::query()->where('id', '>', $inquiry->id)->orderBy('id')->value('id'),
            'next' => Inquiry::query()->where('id', '<', $inquiry->id)->orderByDesc('id')->value('id'),
        ]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(InquiryStatus::class)],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $inquiry->update($data);

        return back()->with('status', 'Inquiry updated.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('status', "The inquiry from {$inquiry->name} has been deleted.");
    }
}
